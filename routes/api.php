<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// put all api protected routes here
Route::middleware('auth:api')->group(function () {
    Route::get('user-details', [UserController::class, 'userDetails']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::post('addPartner', [UserController::class, 'addPartner']);
    Route::get('showMyPartner', [UserController::class, 'showMyPartner']);


});