<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BabyController;

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
    //User 
    Route::get('user-details', [UserController::class, 'userDetails']);
    Route::post('logout', [UserController::class, 'logout']);

    //Partner
    Route::post('addPartner', [UserController::class, 'addPartner']);
    Route::get('showMyPartner', [UserController::class, 'showMyPartner']);

    //Baby
    Route::post('addBaby', [BabyController::class, 'addBaby']);
    Route::get('getBaby', [BabyController::class, 'getBaby']);
    Route::get('ListBabies', [BabyController::class, 'listBabies']);
    Route::delete('deleteBaby', [BabyController::class, 'deleteBaby']);



});