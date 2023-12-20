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
 * A class representing a unknown assistant, you can extend this class for situations where
 * you dont really know about the type of assistant you will be using.
 */
class UnknownAssistant extends Assistant
{

    protected $assistant_id;
    public function __construct(APIConfiguration $api_config = NULL, string $assistant_id)
    {
        parent::__construct($api_config);
        $this->assistant_id = $assistant_id;
    }



    /**
     * To support functions you should override this method in a new implementation.
     */
    public function handleFunction(string $function, array $arguments): string|array
    {

        $response = [
            'success' => false,
            'message' => 'Functions are not supported for this unknown assistant'
        ];

        return $response;
    }
    /**
     * Returns the assistant ID for this unknown assistant.
     */
    public function getAssistantId(): string
    {
        return $this->assistant_id;
    }



    /**
     * Override this function if you wish to use the library in a non-blocking manner where you will save conversations
     * for later use.
     */
    public function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void
    {
        // Default do nothing...
    }

}
