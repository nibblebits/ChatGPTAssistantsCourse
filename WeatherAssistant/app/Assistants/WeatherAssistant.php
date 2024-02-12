<?php

namespace App\Assistants;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;

class WeatherAssistant extends Assistant
{

    public function getAssistantId(): string
    {
        return 'asst_lsHnKLt9FtQMo4F1CNICnoZq';
    }

    private function handleFunctionGetWeather(array $arguments) : array
    {
        $location = $arguments['location'];
        return [
            'success' => true,
            'celcius' => -70,
            'message' => $location . ' is extremly cold, and snowing, dangerous weather conditions'
        ];
    }

    private function handleFunctionHandleWeather(array $arguments) : array
    {
        return [
            'success' => true
        ];
    }

    public function handleFunction(string $function, array $arguments): string|array
    {
        $res = [];
        switch($function)
        {
            case 'get_weather':
                $res = $this->handleFunctionGetWeather($arguments);
            break;

            case 'handle_weather':
                $res = $this->handleFunctionHandleWeather($arguments);
            break;

            default:
                $res = [
                    'success' => false,
                    'message' => 'no such function'
                ];
        }

        return $res;
    }

    public function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void
    {
        // Do nothing.
    }
}