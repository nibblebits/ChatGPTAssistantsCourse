# chatgpt-assistant
Provides clean abstraction layer for the chatgpt assistant API. Build and run assistants with a clean easy to use design, compatible with raw PHP and also Laravel framework.

[Documentation for Installation on Laravel framework](https://github.com/dragonzapeducation/chatgpt-assistant-examples/tree/main/laravel10-api-app)

[Documentation for Installation without Laravel framework](https://github.com/dragonzapeducation/chatgpt-assistant-examples/tree/main/JustSimplePhp)

## Installation

To install the ChatGPT Assistant wrapper, use Composer. Run the following command in your project directory:

```bash
composer require dragonzap/openai-chatgpt-assistant
```

## Simple Usage

```php
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
    
}
```

JessicaAssistant will override the `handleFunction()` method which will be called by ChatGPT when ChatGPT needs to execute functions
```php
 public function handleFunction(string $function, array $arguments): string|array
    {
        $response = [];

        switch($function)
        {
            case 'get_weather':
                $response = ['success' => true, 'message' => 'We will pretend its a sunny day where ever you live'];
                break;

            default:
                $response = [
                    'success' => false,
                    'message' => 'Unknown function'
                ];
        }
        return $response;
    }

```
The `handleFunction` method will respond with the message to send back to ChatGPT both strings and arrays are allowed.
See the full implementation of Jessica Assistant here: https://github.com/dragonzapeducation/chatgpt-assistant-examples/blob/main/JustSimplePhp/src/console-chat-example.php

