<?php

use App\Models\Bot;
use App\Models\Coin;
use App\Models\EmptyResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'WelcomeController@index');

Auth::routes(['verify' => true,'register' => false]);

Route::get('/home', 'HomeController@index')
    ->middleware('auth:web');

Route::get('/botList', 'BotController@list')
    ->middleware('auth:web');


//Route::get('/profile', 'UserController@profile')->middleware('auth:web');
//Route::post('/profile', 'UserController@profileUpdate')->middleware('auth:web');
Route::get('/tokens', 'UserController@tokens')
    ->middleware('auth:web');
Route::post('/tokens', 'UserController@tokensUpdate')
    ->middleware('auth:web');


Route::resource('users', 'UserController')
    ->middleware('auth:web');

Route::resource('languages', 'LanguageController')
    ->middleware('auth:web');

Route::resource('bots', 'BotController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botCategoryGroups', 'BotCategoryGroupController')
    ->middleware(['auth:web','data.transform']);

Route::resource('clients', 'ClientController')
    ->middleware(['auth:web','data.transform']);

Route::resource('conversations', 'ConversationController')
    ->middleware(['auth:web','data.transform']);

Route::resource('maps', 'MapController')
    ->middleware('auth:web');

Route::resource('mapValues', 'MapValueController')
    ->middleware(['auth:web','data.transform']);

Route::resource('sets', 'SetController')
    ->middleware('auth:web');

Route::resource('setValues', 'SetValueController')
    ->middleware(['auth:web','data.transform']);

Route::GET('/test', 'TestController@index')
    ->middleware(['auth:web']);

Route::GET('/test/run', 'TestController@run')
    ->middleware(['auth:web']);

/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/category/fromEmptyResponse'], function () {

    Route::bind('emptyResponseSlug', function ($emptyResponseSlug) {
        try {
            $emptyResponse = App\Models\EmptyResponse::where('slug', $emptyResponseSlug)->firstOrFail();
            return $emptyResponse->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{emptyResponseSlug}', 'CategoryController@createFromEmptyResponse')
        ->middleware(['auth:web','data.transform']);
});


Route::group(['prefix' => '/category/fromClientCategory'], function () {

    Route::bind('clientCategorySlug', function ($clientCategorySlug) {

        try {
            $clientCategory = App\Models\ClientCategory::where('slug', $clientCategorySlug)->firstOrFail();
            return $clientCategory->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{clientCategorySlug}', 'CategoryController@createFromClientCategory')
        ->middleware(['auth:web','data.transform']);
});


Route::resource('categories', 'CategoryController')
    ->middleware(['auth:web','data.transform']);

Route::resource('normalizations', 'NormalizationController')
    ->middleware(['auth:web','data.transform']);

Route::resource('wordSpellings', 'WordSpellingController')
    ->middleware(['auth:web','data.transform']);

Route::resource('wordTransformations', 'WordTransformationController')
    ->middleware(['auth:web','data.transform']);

Route::resource('conversationProperties', 'ConversationPropertyController')
    ->middleware(['auth:web','data.transform']);

Route::resource('clientCategories', 'ClientCategoryController')
    ->middleware(['auth:web','data.transform']);

Route::resource('emptyResponses', 'EmptyResponseController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botProperties', 'BotPropertyController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botWordSpellingGroups', 'BotWordSpellingGroupController')
    ->middleware(['auth:web','data.transform']);

Route::resource('categoryGroups', 'CategoryGroupController')
    ->middleware(['auth:web','data.transform']);

Route::resource('turns', 'TurnController')
    ->middleware(['auth:web','data.transform']);

Route::resource('wordSpellingGroups', 'WordSpellingGroupController')
    ->middleware(['auth:web','data.transform']);

Route::Get('botList/create', 'BotController@create')
    ->middleware(['auth:web','data.transform']);

Route::resource('wildcards', 'WildcardController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botKeys', 'BotKeyController')
    ->middleware(['auth:web','data.transform']);

Route::delete('botRatings/reset', 'BotRatingController@reset')
    ->middleware(['auth:web','data.transform']);

Route::resource('botRatings', 'BotRatingController')
    ->middleware(['auth:web','data.transform']);


/** ---------------------------------------------------------------
 *  BOT EDIT ROUTER
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/bot'], function () {
    Route::bind('botSlug', function ($slug) {
        try {
            $bot = App\Models\Bot::where('slug', $slug)->firstOrFail();
            return $bot->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/properties/{botSlug}/list', 'BotController@botProperties')
    ->middleware('auth:web');
    Route::GET('/properties/{botSlug}/download', 'BotPropertyController@botPropertiesDownload')
    ->middleware('auth:web');
    Route::GET('/keys/{botSlug}/list', 'BotController@botKeys')
    ->middleware('auth:web');


    Route::GET('/categories/{botSlug}/list', 'BotController@botCategoryGroups')
    ->middleware('auth:web');
    Route::GET('/logs/{botSlug}/list', 'BotController@conversations')
    ->middleware('auth:web');
    Route::GET('/logs/{botSlug}/{conversationSlug}', 'BotController@conversations')
    ->middleware('auth:web');
    Route::GET('/plugins/{botSlug}/list', 'BotController@botPlugins')
    ->middleware('auth:web');
    Route::GET('/widget/{botSlug}/list', 'BotController@wigdets')
    ->middleware('auth:web');
    Route::GET('/stats/{botSlug}/list', 'BotController@stats')
    ->middleware('auth:web');
    Route::GET('/{botSlug}/chat', 'BotController@chatForm')
    ->middleware('auth:web');
    Route::POST('/{botSlug}/chat', 'BotController@chat')
    ->middleware('auth:web');
});


/** ---------------------------------------------------------------
 *  UPLOAD ROUTER
 ** -------------------------------------------------------------- */
Route::GET('categoriesUpload', 'CategoryController@uploadForm')
    ->middleware('auth:web');
Route::POST('categoriesUpload', 'CategoryController@upload')
    ->middleware('auth:web');

Route::GET('mapValuesUpload', 'MapValueController@uploadForm')
    ->middleware('auth:web');
Route::POST('mapValuesUpload', 'MapValueController@upload')
    ->middleware('auth:web');

Route::GET('setValuesUpload', 'SetValueController@uploadForm')
    ->middleware('auth:web');
Route::POST('setValuesUpload', 'SetValueController@upload')
    ->middleware('auth:web');

Route::GET('wordSpellingsUpload', 'WordSpellingController@uploadForm')
    ->middleware('auth:web');
Route::POST('wordSpellingsUpload', 'WordSpellingController@upload')
    ->middleware('auth:web');

Route::GET('wordTransformationsUpload', 'WordTransformationController@uploadForm')
    ->middleware('auth:web');
Route::POST('wordTransformationsUpload', 'WordTransformationController@upload')
    ->middleware('auth:web');

Route::GET('botPropertiesUpload', 'BotPropertyController@uploadForm')
    ->middleware('auth:web');
Route::POST('botPropertiesUpload', 'BotPropertyController@upload')
    ->middleware('auth:web');


Route::GET('/categories/{categoryGroupSlug}/download/csv', 'CategoryController@downloadCsv')
    ->middleware('auth:web');
Route::GET('/categories/{categoryGroupSlug}/download/aiml', 'CategoryController@downloadAiml')
    ->middleware('auth:web');
