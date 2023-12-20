<?php
require "vendor/autoload.php";
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\Conversation;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;
use Dragonzap\OpenAI\ChatGPT\RunState;

/**
 * Run the console chat
 * php ./reload-conversation-example.php
 * 
 * An example script on how you can reload conversations, this allows you to use the assistans in a non-blocking manner.
 * You may save the conversation ID's in the database to continue them later.
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
     * In this example we will show how it can be used
     */
    public function saveConversationIdentificationData(ConversationIdentificationData $conversation_id_data): void
    {

        // You extract the save data string which is a base64 json encoded array containing the
        // chatgpt thread id and run id. You then should store this in the Model of your PHP framework
        // that you have created that represents conversations between users and chatgpt.
        // You can use the data string to reload the conversation and state at a future point
        $save_data_string = $conversation_id_data->getSaveDataString(); 

        // For demonstration purposes we will just print it out, you should see it printed many times
        // as the save data changes it needs to be updated in your database in a real application.
        // the library will call this function how ever many times is required to ensure that the save data
        // is always up to date thus allowing you to restore the conversation state at a different time.
        echo 'saveConversationIdentificationData() called: ' . $save_data_string . "\n"; 

    }


    private function handleGetWeatherFunction(array $arguments)
    {
        $success = false;
        $message = 'We could not locate the weather for ' . $arguments['location'] . ' as it is not in our database';

        switch (strtolower($arguments['location'])) {
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

        switch ($function) {
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

// Write a message
$conversation->sendMessage('Hello world!');


// Save data should be obtained after the last conversation action, and the database should be updated 
// every time a new action is preformed to ensure you can keep track of current chatgpt executions.
// Save data string will change with every action so do not forget to reobtain it and store it again in the database
// after every action. The saveConversationIdentificationData method in the Assistant class will be called automatically
// every time its time to save the save data string, thus maintaining the conversation state.

// For the base64 encoded string expect minimum of 130 characters required, better to use 180 characters
// incase of updates to the library.
$base64_encoded_save_data = $conversation->getIdentificationData()->getSaveDataString();
echo $base64_encoded_save_data . "\n";

// To get the openAI thread ID do
$conversation->getIdentificationData()->getConversationId();
// TO get the current run ID do
$conversation->getIdentificationData()->getRunId();


// Uncomment the sleep line to see other than the "still running" message
// In a real application the save data would be stored in the database and the conversation reloaded
// when the user opens the chat once more.
sleep(5);

// Reloading the covnersation later on is accomplished by creating a new ConversationIdentificationData object from the
// previously obtained base64 encoded save data. You can store that base64 encoded string in a database model so conversations can be
// later restored.
$reloaded_conversation = $assistant->loadConversation(ConversationIdentificationData::fromSaveData($base64_encoded_save_data));
if ($reloaded_conversation->getRunState() == RunState::COMPLETED) {
    echo 'Responded:' . $reloaded_conversation->getResponseData()->getResponse();
} else if ($reloaded_conversation->getRunState() == RunState::FAILED) {
    echo ' Conversation failure';
} else if ($reloaded_conversation->getRunState() == RunState::RUNNING) {
    echo 'still running';
} else if ($reloaded_conversation->getRunState() == RunState::NON_EXISTANT) {
    echo 'Must run the conversation first';
} else if ($reloaded_conversation->getRunState() == RunState::QUEUED) {
    echo 'Queued';
} else if ($reloaded_conversation->getRunState() == RunState::INVOKING_FUNCTION) {
    echo 'Function/action is being invoked';
}
// ect ect....


