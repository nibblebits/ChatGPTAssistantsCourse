<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Api\V1\APIController;

class AssistantController extends APIController
{
    public function index()
    {
        return response()->json(['message' => 'Check API reference for details on how to use the API'], 200);
        
    }
}