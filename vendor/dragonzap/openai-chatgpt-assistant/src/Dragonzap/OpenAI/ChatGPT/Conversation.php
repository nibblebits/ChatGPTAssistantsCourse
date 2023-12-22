<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */


namespace Dragonzap\OpenAI\ChatGPT;

use Dragonzap\OpenAI\ChatGPT\Exceptions\IncompleteRunException;
use Dragonzap\OpenAI\ChatGPT\Exceptions\ThreadRunResponseLastError;
use Dragonzap\OpenAI\ChatGPT\Exceptions\UnsupportedRunException;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;
use OpenAI\Responses\Threads\ThreadResponse;


enum RunState: string
{
    // NON_EXISTANT status means that the conversation has not called run() yet or that a previous run() has been handled already
    case NON_EXISTANT = 'non_existant';
    case QUEUED = 'queued';

    case RUNNING = 'running';
    case COMPLETED = 'completed';

    // INVOKING_FUNCTION is returned when chatgpt wants to call a function defined in your assistant.
    // We set this when we are aware and will attempt to invoke this action on your behalf.
    // You do not have to do anything with this.
    case INVOKING_FUNCTION = 'invoking_function';
    case FAILED = 'failed';

    case UNKNOWN = 'unknown';
}

/**
 * Represents a conversation
 */
class Conversation
{
    protected Assistant $assistant;
    protected ThreadResponse $thread;

    protected ThreadRunResponse|null $current_run;

    protected ResponseData|null $response_data;

    public function __construct(Assistant $assistant, ThreadResponse $thread, ThreadRunResponse|null $current_run)
    {
        $this->assistant = $assistant;
        $this->thread = $thread;
        $this->current_run = $current_run;
        $this->response_data = null;
        if ($this->current_run) {
            $this->response_data = new ResponseData();
        }
    }

    private function setCurrentRun(ThreadRunResponse|null $current_run)
    {
        $this->current_run = $current_run;
        $this->assistant->saveConversationIdentificationData($this->getIdentificationData());
    }

    /**
     * @return ConversationIdentificationData Returns an object which identifies the current conversation
     */
    public function getIdentificationData(): ConversationIdentificationData
    {
        $thread_id = $this->thread->id;
        $run_id = null;
        if ($this->current_run) {
            $run_id = $this->current_run->id;
        }
        return new ConversationIdentificationData($thread_id, $run_id);
    }

    public function sendMessage(string $message, string $role = 'user', bool $autorun = true): void
    {
        $this->assistant->getOpenAIClient()->threads()->messages()->create($this->thread->id, [
            'role' => $role,
            'content' => $message,
        ]);
        if ($autorun) {
            $this->run();
        }
    }

    public function run(): void
    {
        $this->response_data = new ResponseData();
        $current_run = $this->assistant->getOpenAIClient()->threads()->runs()->create(
            threadId: $this->thread->id,
            parameters: [
                'assistant_id' => $this->assistant->getAssistantId(),
            ],
        );

        $this->setCurrentRun($current_run);

    }

    public function getThreadResponse(): ThreadResponse
    {
        return $this->thread;
    }

    private function getRunStateFromOpenAIRunState(string $state): RunState
    {
        $run_state = RunState::UNKNOWN;

        switch ($state) {
            case 'queued':
                $run_state = RunState::QUEUED;
                break;

            case 'in_progress':
                $run_state = RunState::RUNNING;
                break;

            case 'completed':
                $run_state = RunState::COMPLETED;
                break;

            case 'requires_action':
                // We will automatically invoke the function later, so mark it as invoking function
                $run_state = RunState::INVOKING_FUNCTION;
                break;

            case 'failed':
            case 'expired':
            case 'cancelled':
                $run_state = RunState::FAILED;
                break;
        }


        return $run_state;
    }

  
    /**
     * Retrieves the response data for the current or last run.
     *
     * This method checks if the current run is completed and then fetches the response data.
     * If the current run is not completed, it throws an IncompleteRunException.
     *
     * If the current run is completed and this method is called multiple times then it will fetch the last 
     * completed run ResponseData
     * 
     * @throws IncompleteRunException If the current run's status is not 'completed'.
     * @return ResponseData The response data object with the response set.
     */
    public function getResponseData(): ResponseData
    {
        // If we have a current run then check if the current run is completed
        if ($this->current_run && $this->current_run->status != 'completed') {
            throw new IncompleteRunException(
                'The job status is not yet completed. ' .
                'Run Conversation::getRunState() to check the current run status. ' .
                'If it returns RunState::COMPLETED, you can then retrieve the response.'
            );
        }

        if (!$this->response_data) {
            throw new IncompleteRunException(
                'You have not run the conversation yet so theres no data to retreive.'
            );
        }
        // We have a current run that is completed then update the response_data. Otherwise its an old 
        // run that we previously retrived response data for.
        if ($this->current_run) {
            // Fetch the response
            $response = $this->assistant->getOpenAIClient()
                ->threads()
                ->messages()
                ->list($this->thread->id, ['limit' => 1]);

            // Set the response_data
            $this->setCurrentRun(null);
            $this->response_data->setResponse($response->data[0]->content[0]->text->value);
        }

        return $this->response_data;
    }

    /**
     * Blocks the execution until ChatGPT responds to a message or there was a failure of some kind
     * 
     * Warning: Ideally should only be used in API's or console applications, avoid use if possible as long timeouts
     * disrupt user experience and strain the web server.
     *
     * @throws ThreadRunResponseLastError If there is an error with the current run.
     */
    public function blockUntilResponded(): RunState
    {
        $run_state = $this->getRunState();
        while ($run_state != RunState::COMPLETED && $run_state != RunState::FAILED) {
            sleep(1);
            $run_state = $this->getRunState();
        }

        return $run_state;
    }
    

    private function handleRequiresAction()
    {

        // We dont support action types that are not of submit_tool_outputs
        if ($this->current_run->requiredAction->type != 'submit_tool_outputs') {
            throw new UnsupportedRunException('The library does not yet handle action types of ' . $this->current_run->requiredAction->type);
        }

        $action_function_tool_calls = $this->current_run->requiredAction->submitToolOutputs->toolCalls;
        $tool_outputs = [];

        foreach ($action_function_tool_calls as $action_function_tool_call) {
            if ($action_function_tool_call->type != 'function') {
                throw new UnsupportedRunException('The library does not yet handle functions that are not of type function');
            }
            $tool_call_id = $action_function_tool_call->id;
            $function_name = $action_function_tool_call->function->name;
            $function_arguments = json_decode($action_function_tool_call->function->arguments, true);
            $function_response = $this->assistant->handleFunction($function_name, $function_arguments);

            // Add the function call to the response data
            $this->response_data->addFunctionCall(new GPTFunctionCall($function_name, $function_arguments, $function_response));

            if (is_array($function_response)) {
                // By default we JSON encode for arrays and paass back only strings.
                $function_response = json_encode($function_response);
            }

            $tool_outputs[] = ['tool_call_id' => $tool_call_id, 'output' => $function_response];
        }

        // Now we have called the function and got a response lets pass it back to chatgpt
        $current_run = $this->assistant->getOpenAIClient()->threads()->runs()->submitToolOutputs(
            threadId: $this->thread->id,
            runId: $this->current_run->id,
            parameters: [
                'tool_outputs' => $tool_outputs,
            ]
        );

        $this->setCurrentRun($current_run);

    }

    /**
     * Retrieves the current state of the run associated with this thread/conversation.
     * This method checks whether the current run exists and, if so, updates its state
     * based on the latest data from the OpenAI client. It handles any errors encountered 
     * during the run and responds appropriately if the run requires further action.
     * 
     * 
     * NOTE: even though we throw a ThreadRunResponseLastError when an error occurs, the RunState can still be "failed" without an exception being thrown
     * as a failed run does not necessarily warrent an exception. Only an API error will cause an exception.
     * 
     * @return RunState The current state of the run.
     * @throws ThreadRunResponseLastError If there is an error with the current run. 
     */
    public function getRunState(): RunState
    {
        if (!$this->current_run) {
            return RunState::NON_EXISTANT;
        }

        $this->setCurrentRun($this->assistant->getOpenAIClient()->threads()->runs()->retrieve(
            threadId: $this->thread->id,
            runId: $this->current_run->id,
        ));

        if ($this->current_run->lastError) {
            // We have an error of some kind.
            throw new ThreadRunResponseLastError($this->current_run->lastError->message, $this->current_run->lastError->code);
        }

        if ($this->current_run->status == 'requires_action') {
            $this->handleRequiresAction();
        }

        return $this->getRunStateFromOpenAIRunState($this->current_run->status);
    }

}
