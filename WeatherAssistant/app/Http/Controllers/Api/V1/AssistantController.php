<?php
namespace App\Http\Controllers\Api\V1;
use App\Exceptions\Assistants\NoSuchAssistantException;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Requests\Api\V1\AssistantSendMessageRequest;
use Dragonzap\OpenAI\ChatGPT\Assistant;
use Dragonzap\OpenAI\ChatGPT\ConversationIdentificationData;

class AssistantController extends APIController
{

    private function getAssistant(string $id): Assistant
    {
        if (!isset(config('dragonzap.assistants')[$id]))
        {
            throw new NoSuchAssistantException('No such assistant');
        }

        return new (config('dragonzap.assistants')[$id]['class'])();
    }

    public function index()
    {
        return response()->json(['message' => 'Check API reference for details on how to use the API'], 200);
    }

    public function sendMessage(string $id, AssistantSendMessageRequest $request)
    {
        $assistant = null;
        try
        {
            $assistant = $this->getAssistant($id);
        }catch(NoSuchAssistantException $ex)
        {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 422);
        }

        $message = $request->message;

        if ($request->has('save_data_string'))
        {
            $conversation = 
                $assistant->loadConversation(
                    ConversationIdentificationData::fromSaveData($request->save_data_string)
                );
        }
        else
        {
            $conversation = $assistant->newConversation();
        }
        $conversation->sendMessage($message);
        $conversation->blockUntilResponded();

        return response()->json([
            'message' => $message,
            'response' => $conversation->getResponseData()->getResponse(),
            'save_data_string' => $conversation->getIdentificationData()->getSaveDataString()
        ], 200);
    }
}