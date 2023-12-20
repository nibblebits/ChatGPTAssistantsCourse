<?php
require "vendor/autoload.php";
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;

/**
 * Run the console chat
 * php ./console-chat-example.php
 * 
 * Start typing questions and get answers
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
    }

    /**
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

    /**
     * The handleFunction method will be called anytime your ChatGPT assistant executes a function.
     * Data returned is stored in the ResponseData object of your ChatGPT conversation. This data can then
     * be used to see how the function was handled if needed or also passed to the frontend so that the frontend can
     * perform actions on behalf of chatgpt such as showing a discount voucher to the user.
     */
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
$conversation = $assistant->newConversation();

while(1)
{
    $input_message = fgets(STDIN);
    echo 'User:' . $input_message . "\n";
    $conversation->sendMessage($input_message);
    $conversation->blockUntilResponded();
    
    echo 'Assistant: ' . $conversation->getResponseData()->getResponse() . "\n";

    // Easily get the function calls that took place during the ChatGPT run.
    // You can pass the function call array to your front end so your javascript can perform
    // actions on behalf of the user instructed by ChatGPT. 
    print_r($conversation->getResponseData()->getFunctionCalls());
    // OUTPUT FROM PRINT_R
    // [0] => Dragonzap\OpenAI\ChatGPT\GPTFunctionCall Object
    // (
    //     [function_name:protected] => get_weather
    //     [function_arguments:protected] => Array
    //         (
    //             [location] => Cardiff
    //         )

    //     [response:protected] => Array
    //         (
    //             [success] => 1
    //             [message] => The weather in wales, cardiff is Rainy today
    //         )

    // )

    
}

