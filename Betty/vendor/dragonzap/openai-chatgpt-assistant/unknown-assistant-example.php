<?php
require "vendor/autoload.php";
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\UnknownAssistant;

/**
 * 
 * Theres times where your chatgpt chats are so simple you may not want to have to create your own assistant class
 * in these situations you can use the UnknownAssistant and pass the assistant ID directly to it.
 * 
 * 
 * Run the unknown assistant example
 * php ./unknown-assistant-example
 * 
 * Start typing questions and get answers
 */

// Replace the API Key with your own chatgpt API key
$assistant = new UnknownAssistant(new APIConfiguration('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'), 'asst_0q46BUiesPu5XStGHufJVCba');
$conversation = $assistant->newConversation();

while(1)
{
    $input_message = fgets(STDIN);
    echo 'User:' . $input_message . "\n";
    $conversation->sendMessage($input_message);
    $conversation->blockUntilResponded();
    
    echo 'Assistant: ' . $conversation->getResponseData()->getResponse() . "\n";
    
}

