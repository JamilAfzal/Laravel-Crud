<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

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
Route::post('create-user',[UserController::class,'createuser']);
Route::get('show-users',[UserController::class , 'showusers']);
Route::get('show-users/{id}',[UserController::class,'userdetail']);
Route::put('update-users/{id}',[UserController::class,'updateuser']);
Route::delete('delete-users/{id}',[UserController::class,'deleteuser']);
Route::post('userss',[UserController::class,'createuserwithphone']);
Route::post('userspost',[UserController::class,'postusers']);
Route::get('allusers',[UserController::class,'allusers']);
Route::get('userrdetail/{id}',[UserController::class,'userrdetail']);
Route::put('save-users/{id}',[UserController::Class,'saveusers']);
Route::post('posted-users',[UserController::class,'postinguserdetails']);
Route::get('userdeets/{id}',[UserController::class,'showtheusers']);
Route::get('userdeet',[UserController::class,"userdeet"]);
Route::put('updatingthedeets/{id}',[UserController::class,"updatingthedeets"]);