<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'prefix' => 'v1.0'

], function () use ($router) {

    Route::group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', ['uses' => Api\V1\AuthController::class . '@login', 'as' => 'mobile-login']);
        $router->post('registration', ['uses' => Api\V1\AuthController::class . '@registration', 'as' => 'mobile-registration']);
    });
});