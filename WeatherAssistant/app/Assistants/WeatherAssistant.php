<?php

namespace App\Assistants;
use Cmfcmf\OpenWeatherMap;
use Http\Factory\Guzzle\RequestFactory;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;

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
        $response = ['state' => 'failed', 'message' => 'Unknown error has occured'];
        $httpRequestFactory = new RequestFactory();
        $httpClient = GuzzleAdapter::createWithConfig([]);
        $owm = new OpenWeatherMap(config('services.openweathermap.key'), $httpClient, $httpRequestFactory);

        $location = $arguments['location'];
        try {
            $forecast = $owm->getWeather($location, 'metric');
            $response = [
                'state' => 'success',
                'forecast' => print_r($forecast, true)
            ];
        } catch(\Exception $ex) {
            $response = ['state' => 'failed', 'message' => 'Problem obtaining the weather for city: ' . $location];
        }
        return $response;
    }

    private function handleFunctionHandleWeather(array $arguments) : array
    {
        $weather_type = $arguments['weather_type'];
        $icon_url = asset('images/chat/icons/weather/' . $weather_type . '.png');
        return [
            'success' => true,
            'icon_url' => $icon_url
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