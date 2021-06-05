<?php

use App\Models\Bot;
use App\Models\EmptyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/talk/bot', 'TalkAPIController@store');
Route::post('/talk/meta', 'TalkAPIController@old_meta');



/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/meta'], function () {

    Route::bind('botMetaSlug', function ($botMetaSlug) {
        try {
            $bot = App\Models\Bot::where('slug', $botMetaSlug)->firstOrFail();
            return $bot->slug;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{botMetaSlug}', 'TalkAPIController@meta');
});
