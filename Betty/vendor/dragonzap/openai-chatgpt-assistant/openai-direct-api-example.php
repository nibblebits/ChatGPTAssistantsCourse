<?php
require "vendor/autoload.php";
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;
use Dragonzap\OpenAI\ChatGPT\RunState;

/**
 * In this example we show how to obtain direct API access for an assistant to use features not implemented
 * in the abstraction layer.
 */
class JessicaAssistant extends Assistant
{

    public function __construct($api_config = NULL)
    {
        parent::__construct($api_config);
    }

    
    /**
     * You should replace the assistant ID with your own chatgpt assistant id.
     */
    public function getAssistantId(): string
    {
        return 'asst_0q46BUiesPu5XStGHufJVCba';
    }    /**
    * This function is invoked automatically everytime the library wants us to save the conversation data 
    * to our database. This only has to be implemented if you plan to store conversations over multiple requests
    * In this example we handle it all in a console application so we dont need to maintain any state.
    */
   public function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void
   {
       // Since we are blocking we dont need to save this identification data
       // the whole conversation is handled in one request.
   }


    private function handleGetWeatherFunction(array $arguments)
    {
        $success = false;
        $message = 'We could not locate the weather for ' . $arguments['location'] . ' as it is not in our database';

        switch(strtolower($arguments['location']))
        {
            case 'cardiff':
                $success = true;
                $message = 'The weather in wales, cardiff is Rainy today';
            break;

            case 'london':
                $success = false;
                $message = 'As usual england is freezing';
            break;

            case 'perth':
                $success = false;
                $message = 'Australia, Perth is very hot at 45 Celcius everyone is cooking';
            break;
        }
        return [
            'success' => $success,
            'message' => $message,
        ];
    }
    public function handleFunction(string $function, array $arguments): string|array
    {
        $response = [];

        switch($function)
        {
            case 'get_weather':
                $response = $this->handleGetWeatherFunction($arguments);
                break;

            default:
                $response = [
                    'success' => false,
                    'message' => 'Unknown function'
                ];
        }
        return $response;
    }

}

// Replace the API Key with your own chatgpt API key
$assistant = new JessicaAssistant(new APIConfiguration('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'));
$openai = $assistant->getOpenAIClient();

// With the openai client obtained we can now use direct api features like so
$assistants = $openai->assistants()->list([
    'limit' => 10,
]);

print_r($assistants->toArray());
// NOTE THE ASSISTANTS VARIABLE ABOVE DOESNT USE OUR ABSTRACTION LAYER, ACCESSING THE API DIRECTLY
// LEAVES YOU BINDED TO THIS LIBRARY: https://github.com/openai-php/client#testing

