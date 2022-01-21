<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminMessagesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/hh7hl7djVWDIAVhFDRMAwZ1tj0Og2v4PWyj4PZ/web-hook', [AdminMessagesController::class, 'getWebhook']);

Route::get('/set-webhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://cbt.dispenda.online/api/hh7hl7djVWDIAVhFDRMAwZ1tj0Og2v4PWyj4PZ/web-hook']);
    return response()->json($response);
});

Route::get('/remove-webhook', function () {
    $response = Telegram::removeWebhook();
    return response()->json($response);
});

Route::get('/slot', function () {
    return response()->json([
        'status' => 'sukses',
        'slot' => 0 
        // 'slot' => mt_rand(0, 1) != 0 ? mt_rand(0, 200) : 0 
    ]);
});

Route::post('/get-updates', function () {
    $updates = Telegram::getUpdates();
    return response()->json($updates);
});



Route::post('bot/sendmessage', function () {
    Telegram::sendMessage([
        'chat_id' => 'RECIPIENT_CHAT_ID',
        'text' => 'Hello world!'
    ]);
    return;
});
