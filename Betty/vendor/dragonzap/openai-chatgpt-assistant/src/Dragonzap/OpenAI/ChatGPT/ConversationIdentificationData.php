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
 * Conversation identification data can be used to reload conversations at a later time.
 * 
 * Obtain save data and store it in the database, using the save data you ca nreload the ConversationIdentificationData.
 */
class ConversationIdentificationData
{
    private string $conversationId;
    private string|null $runId;

    public function __construct(string $conversationId, string|null $runId)
    {
        $this->conversationId = $conversationId;
        $this->runId = $runId;  
    }

    /**
     * Returns the thread ID that can be used directly with the openai api for the openai conversation
     */
    public function getConversationId(): string
    {
        return $this->conversationId;   
    }

    /**
     * Returns the run ID if any. If the conversation was not running this will be NULL
     */
    public function getRunId(): string |null
    {
        return $this->runId;
    }

    /**
     * Returns a base64 encoded data string that can be reloaded to resume a conversation from its last point.
     */
    public function getSaveDataString() : string
    {
        return base64_encode(json_encode([
            'conversation_id' => $this->conversationId,
            'run_id' => $this->runId,
        ]));
    }

    
    /**
     * Returns ConversationIdentificationData from the ConverstationIdentificationData save data that was obtained
     * by caling the ConversationIdentificationData::getSaveDataString() method.
     * 
     * @throws \InvalidArgumentException Thrown if this is not a valid save data
     */
    public static function fromSaveData(string $save_data_string)
    {
        $decoded_id = json_decode(base64_decode($save_data_string), true);
        if (!isset($decoded_id['conversation_id']))
        {
            throw new \InvalidArgumentException('This ID provided is not a valid ConversationIdentificationData ID');
        }

        $conversation_id = $decoded_id['conversation_id'];
        $run_id = null;
        if (isset($decoded_id['run_id']))
        {
            $run_id = $decoded_id['run_id'];
        }
        return new self($conversation_id, $run_id);
    }


}
