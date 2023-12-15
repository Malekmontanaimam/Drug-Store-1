<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Admin0Controller;
use GuzzleHttp\Middleware;
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
Route::post('/register',[AuthController::class,'Register']);

Route::post('/login',[AuthController::class,'Login']);
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/forgetpassword',[AuthController::class,'forgetpassword']);
    Route::post('/resetpassword',[AuthController::class,'resetpassword']);
    Route::post('/search',[AuthController::class,'search']);
  
    Route::post('/logout',[AuthController::class,'Logout']);

});

Route::post('/admin/login',[Admin0Controller::class,'Login']);
Route::post('/admin/register',[Admin0Controller::class,'register']);


Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/admin/logout',[Admin0Controller::class,'Logout']);
    Route::post('/admin/InsertProduct',[Admin0Controller::class,'InsertProduct']);
    Route::post('/admin/InsertCategories',[Admin0Controller::class,'InsertCategories']);
    Route::get('/admin/getProduct',[Admin0Controller::class,'getProduct']);
    Route::get('/admin/getCategories',[Admin0Controller::class,'getCategories']);
});