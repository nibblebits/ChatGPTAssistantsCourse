<?php
use Dragonzap\OpenAI\ChatGPT\APIConfiguration;
use Dragonzap\OpenAI\ChatGPT\UnknownAssistant;
require './vendor/autoload.php';

$betty = new UnknownAssistant(new APIConfiguration('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'), 
'asst_43dJhZ11hFgjynmsq9GxqUJV');
$conversation = $betty->newConversation();
$conversation->sendMessage('Hello how are you today?');
$conversation->blockUntilResponded();

echo $conversation->getResponseData()->getResponse();
