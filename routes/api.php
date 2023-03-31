<?php

use App\Http\Controllers\Api\Authentication\AuthorizationController;
use App\Http\Controllers\Api\Authentication\RegistrationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/registration', [RegistrationController::class, 'registration']);
Route::post('/login', [AuthorizationController::class, 'login']);

Route::post('/category/create', [CategoryController::class, 'create']);
Route::post('/category/update', [CategoryController::class, 'update']);
Route::post('/category/delete', [CategoryController::class, 'delete']);
Route::get('/category/list', [CategoryController::class, 'list']);

Route::post('/product/create', [ProductController::class, 'create']);
Route::post('/product/update', [ProductController::class, 'update']);
Route::post('/product/delete', [ProductController::class, 'delete']);
Route::post('/product/concrete', [ProductController::class, 'concrete']);
Route::post('/product/category', [ProductController::class, 'getByCategory']);
Route::get('/product/list', [ProductController::class, 'list']);

