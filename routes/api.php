<?php

use App\Models\StockInward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockInwardController;
use App\Http\Controllers\StockOutwardController;

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
Route::controller(LoginController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

/*
    Add and See Vender
*/
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('/vendor/show', [VendorController::class, 'show']);
    Route::post('/vendor/create', [VendorController::class, 'store'])->name('vendor.create');



    /*Ingredients:- 
    Note:- add ratelimiter that only 1time it can be hit in a minute
    1.)Create ingredient order
    2.)display ingredient order
    3.)update
    4.)delete
    */
    Route::controller(IngredientController::class)
        ->group(function () {
            Route::get('/ingredients/available', 'index');
            Route::post('/ingredients/create', 'store');
            Route::put('/ingreidents/update/{ingredient}', 'update');
            Route::delete('/ingredients/delete/{ingredient}', 'destroy');
        });

    Route::controller(StockInwardController::class)
        ->group(function () {
            Route::get('/item/available/', 'index');
            Route::post('/item/buy', 'store');
            Route::patch('/item/update/{id}', 'update');
        });
    /*
        ROute to handle Recipe
    */
    Route::controller(RecipeController::class)
        ->group(function () {
            // Route::get('/item/available/', 'index');
            Route::post('/recipe/create', 'store');
            Route::post('recipes/ingredients/{recipe}', 'addIngredients');
        });
});

/*
Managers Rotes goes hewre:-
*/

Route::middleware(['auth:sanctum','manager'])->group(function () {

    Route::controller(StockOutwardController::class)
        ->group(function () {
            // Route::get('/item/available/', 'index');
            Route::post('/use', 'store');
            Route::put('/use/update/{id}', 'update');
        });

        Route::get('/report/manager',[StockController::class,'index']);
});
