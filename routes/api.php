<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    //Route::post('refresh', 'refresh');
});

Route::controller(UserController::class)->group(function () {
    Route::get('users/index', 'index');
    Route::get('users/show/{id}', 'show');
    Route::put('users/update/{id}', 'update');
    Route::delete('users/delete/{id}', 'destroy');
    Route::get('users/getinfo', 'getinfo');
});

Route::controller(AccountsController::class)->group(function () {
    Route::get('accounts/index', 'index');
    Route::post('accounts/create', 'create');
    Route::get('accounts/show/{id}', 'show');
    Route::put('accounts/update/{id}', 'update');
    Route::delete('accounts/delete/{id}', 'destroy');
    Route::put('accounts/add_users/{id}', 'addUsersToAccount');
    Route::put('accounts/remove_users/{id}', 'removeUsersFromAccount');
    Route::post('accounts/filter', 'getInformationFiltered');

});