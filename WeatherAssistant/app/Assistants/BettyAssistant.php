<?php

namespace App\Assistants;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;

class BettyAssistant extends Assistant
{

    public function getAssistantId(): string
    {
        return 'asst_43dJhZ11hFgjynmsq9GxqUJV';
    }

    public function handleFunction(string $function, array $arguments): string|array
    {
        return 'No functions implemented';
    }

    public function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void
    {
        // Do nothing.
    }
}