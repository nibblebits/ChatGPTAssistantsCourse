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

    public function handleFunctionFillCalendar(array $arguments) : array
    {
        $response = [
            'success' => true,
            'message' => 'The calendar entry has been set'
        ];

        $timestamp = strtotime($arguments['datetime']);
        if ($timestamp < time()) {
            $response = [
                'success' => false,
                'message' => 'You cannot timetravel into the past.'
            ];
        }

        return $response;
    }

    public function handleFunction($function, array $arguments): array|string
    {
        $response = [
            'success' => 'failed',
            'message' => 'No such function'
        ];

        switch($function) {
            case 'fill_calendar':
                $response = $this->handleFunctionFillCalendar($arguments);
            break;
        }

        return $response;

    }


    public function saveConversationIdentificationData(Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData $conversation_id_data): void
    {

    }

}

$betty = new BettyAssistant(new APIConfiguration('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'));
$conversation = $betty->newConversation();

while(true)
{
    $input_line = fgets(STDIN);
    $conversation->sendMessage($input_line);
    try {
        $conversation->blockUntilResponded();
    } catch (ThreadRunResponseLastError $ex) {
        throw $ex;
    }

    echo 'Betty: ' . $conversation->getResponseData()->getResponse() . "\n";
    
}