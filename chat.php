<?php
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\Exceptions\ThreadRunResponseLastError;
use Dragonzap\OpenAI\ChatGPT\UnknownAssistant;

require './vendor/autoload.php';

class BettyAssistant extends Assistant
{
    public function getAssistantId(): string
    {
        return 'asst_43dJhZ11hFgjynmsq9GxqUJV';
    }

    public function handleFunction($function, array $arguments): array|string
    {
        return 'No functions are implemented';
    }


    public function saveConversationIdentificationData(Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData $conversation_id_data): void
    {

    }

}

$betty = new BettyAssistant(new APIConfiguration('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'));
$conversation = $betty->newConversation();
$conversation->sendMessage('Make a calendar entry for next week on tuesday at 4 PM in London');
try {
    $conversation->blockUntilResponded();
} catch (ThreadRunResponseLastError $ex) {
    throw $ex;
}
echo $conversation->getResponseData()->getResponse();
