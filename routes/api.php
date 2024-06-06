<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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

// Route::post('/register',[RegistrationController::class,'register']);
// Route::post('/login',[LoginController::class,'login'])->name('login');

//Two Login Apis user table
// 1.) Admin Login
// 2.) Manager login
Route::controller(LoginController::class)->group(function(){
    Route::post('/login','login')->name('login');
    Route::get('/logout','logout')->name('logout')->middleware('auth:sanctum');
});


/*Ingredients:- 
    Note:- add ratelimiter that only 1time it can be hit in a minute
    1.)Create ingredient order
    2.)display ingredient order
    3.)update
    4.)delete
*/  

/*

*/