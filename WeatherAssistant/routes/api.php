<?php

use App\Http\Controllers\Api\V1\AssistantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// V1 API ROUTES
// Unauthenticated routes

Route::get('/v1/assistants', [AssistantController::class,'index'])->name('assistants.home');
Route::post('/v1/assistant/{id}/send_message', [AssistantController::class,'sendMessage'])->name('assistant.send_message');