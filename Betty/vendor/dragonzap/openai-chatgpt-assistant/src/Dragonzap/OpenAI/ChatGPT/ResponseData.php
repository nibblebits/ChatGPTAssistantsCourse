<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */


namespace Dragonzap\OpenAI\ChatGPT;


/**
 * Represents response data from chatgpt, this object can be obtained after ChatGPT has replied
 * from a message you sent it. The response data contains the text response from ChatGPT along with all
 * function calls that ChatGPT made and the function call responses.
 * 
 * You can extract those function calls to use for debugging purposes or for your front end to be able
 * to do something with the data that was obtained.
 */
class ResponseData
{
    protected string|null $response;

    // GPTFunctionCall[]
    protected array|null $function_calls;

    public function __construct(string|null $response = null, array|null $function_calls = null)
    {
        $this->response = $response;
        $this->function_calls = $function_calls;
        if (!$this->function_calls) {
            $this->function_calls = [];
        }


        foreach ($this->function_calls as $function_call) {
            if (!($function_call instanceof GPTFunctionCall)) {
                throw new \InvalidArgumentException('Provided function_calls must be an array of GPTFunctionCall');
            }
        }
    }


    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
    public function getResponse(): string
    {
        return $this->response;
    }

    public function addFunctionCall(GPTFunctionCall $function_call): void
    {
        if (!($function_call instanceof GPTFunctionCall)) {
            throw new \InvalidArgumentException('The function call must be of type GPTFunctionCall');
        }

        $this->function_calls[] = $function_call;
    }
    /**
     * Retrieves all function calls and their corresponding responses.
     *
     * IMPORTANT: When reloading a completed conversation, the array of function calls will be empty. 
     * This is because function calls are not preserved across loaded and saved conversation states.
     * The getFunctionCalls function will only return function calls for sessions completed in a single instance 
     * without any interruptions.
     *
     * To ensure a response is received after sending a message, utilize the Conversation::blockUntilResponded() method 
     * following the Conversation::sendMessage method. 
     * This ensures the function calls array is populated, as it waits for either a ChatGPT response or an error occurrence.
     *  However, if you choose not to use block and require access to the function calls, 
     * it's crucial to save them immediately when the conversation state becomes INVOKING_FUNCTION. Failing to do so will result in no record of the function calls upon later 
     * reloading the object.
     * @return array An array of GPTFunctionCall objects.
     */
    public function getFunctionCalls(): array
    {
        return $this->function_calls;
    }
}
