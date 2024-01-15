<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */


namespace Dragonzap\OpenAI\ChatGPT;

use OpenAI;
use Exception;

/**
 * An abstract class representing an Assistant.
 * 
 * This class serves as a blueprint for creating various types of assistants.
 * Each assistant will have its own implementation of the handleFunction method.
 */
abstract class Assistant
{
    protected APIConfiguration|null $api_config;
    protected OpenAI\Client $client;
    public function __construct(APIConfiguration $api_config = NULL, )
    {
        $this->api_config = $api_config;
        if ($this->api_config == NULL) {
            try {
                $this->api_config = new APIConfiguration(config('dragonzap.openai.key'));
            } catch (Exception $e) {
                throw new Exception('If you do not provide a ' . APIConfiguration::class . ' then you must be using this module within Laravel framework. Details:' . $e->getMessage());
            }
        }

        $this->client = OpenAI::client($this->api_config->getApiKey());
    }

    public function getApiConfiguration(): APIConfiguration
    {
        return $this->api_config;
    }

    public function getOpenAIClient(): OpenAI\Client
    {
        return $this->client;
    }

    public function newConversation(): Conversation
    {
        $response = $this->client->threads()->create([]);
        return new Conversation($this, $response, null);
    }

    public function loadConversation(ConversationIdentificationData $conversation_id_data): Conversation
    {
        $thread = $this->client->threads()->retrieve($conversation_id_data->getConversationId());
        $run = null;
        $run_id = $conversation_id_data->getRunId();
        if ($run_id) {
            $run = $this->client->threads()->runs()->retrieve($thread->id, $run_id);
        }
        return new Conversation($this, $thread, $run);
    }

    /**
     * 
     * The creator of an assistant should return the assistant ID here, generally this would be returned directly unless you plan
     * to pass the ID into a constructor of some kind.
     * @return string Returns the assistant ID for the assistant
     */
    public abstract function getAssistantId(): string;

    /**
     * Handles a specific function required by the assistant.
     * 
     * @param string $function The name of the function to handle.
     * @param array $arguments An array of arguments passed for the function
     * @return string|array The result or response of the handled function either as a string or an array
     */
    public abstract function handleFunction(string $function, array $arguments): string|array;


    /**
     * Invoked for persisting conversation identification data in the database.
     * This method is essential in scenarios where your application handles non-blocking, multi-request ChatGPT conversations. 
     * Implement this method to ensure conversation continuity across different requests. 
     * If your application operates in a blocking mode and doesn't need to track conversation states across multiple requests, 
     * you may keep this implementation empty.
     * 
     * If you choose to implement this method you must call the getSaveDataString() method on the ConversationIdentificationData object
     * you then store the string in your database where your chatgpt conversation is stored. 
     * 
     * You will then be able to call loadConversation on the Assistant object to reload your conversation at a later time.
     * 
     * Failure to implement this method will cause problems for you in the event your application is non-blocking,
     * for example in cases where conversations are stored in the database and users can come and go as they please.
     * 
     * You don't need to implement this method in cases where you deal with entire conversations in one request.
     * 
     * Example implementation:
     * public function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void;
     * {
     *    $laravel_chatgpt_convo = LaravelChatgptConvo::findOrFail($my_local_conversation_database_id);
     *    $laravel_chatgpt_convo->saved_state = $conversation_id_data->getSaveDataString();
     *    $laravel_chatgpt_convo->save();
     * }
     * 
     * In the future you could then reload the conversation using the saved_state that has been saved into the database.
     * For reloading take a look at the Conversation::loadConversation method, you will need to provide a ConversationIdentificationData
     * that represents the conversation you want to reload. You can obtain this by calling the ConversationIdentificationData::fromSavedData method and pass
     * the saved_state you saved earlier.
     *
     */
    public abstract function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void;
    

}
