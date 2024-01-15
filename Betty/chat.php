<?php
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;
use Dragonzap\OpenAI\ChatGPT\Exceptions\ThreadRunResponseLastError;
use Dragonzap\OpenAI\ChatGPT\Exceptions\TimeoutException;
use Dragonzap\OpenAI\ChatGPT\RunState;
use Dragonzap\OpenAI\ChatGPT\UnknownAssistant;

require './vendor/autoload.php';

class BettyAssistant extends Assistant
{
    public function getAssistantId(): string
    {
        return 'asst_43dJhZ11hFgjynmsq9GxqUJV';
    }

    public function handleFunctionFillCalendar(array $arguments): array
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

        switch ($function) {
            case 'fill_calendar':
                $response = $this->handleFunctionFillCalendar($arguments);
                break;
        }

        return $response;

    }


    public function saveConversationIdentificationData(Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData $conversation_id_data): void
    {
        echo 'FUNCTION HIT' . "\n";
    }

}

$betty = new BettyAssistant(new APIConfiguration('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'));
$conversation = null;
$save_data_string = $argv[1];
$message = $argv[2];

if ($save_data_string == 'na')
{
    $conversation = $betty->newConversation();
}
else
{
    $conversation = $betty->loadConversation(ConversationIdentificationData::fromSaveData($save_data_string));
}

$run_state = $conversation->getRunState();
echo 'RunState: ' . $run_state->value . "\n";

if ($run_state == RunState::COMPLETED)
{
    echo 'Betty: ' . $conversation->getResponseData()->getResponse() . "\n";
}

if ($message != 'na')
{
    $conversation->sendMessage($message);
}

echo 'Saved data string: ' . $conversation->getIdentificationData()->getSaveDataString() . "\n";

