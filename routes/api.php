<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);
Route::get('/login', [RegisterController::class, 'login_failed'])->name('login');

Route::middleware('auth:sanctum')->group( function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/types', [TypeController::class, 'index']);
    Route::get('/types/{id}', [TypeController::class, 'show']);
    Route::post('/types', [TypeController::class, 'store']);
    Route::put('/types/{id}', [TypeController::class, 'update']);
    Route::delete('/types/{id}', [TypeController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/resources', [ResourceController::class, 'index']);
    Route::get('/resources/{id}', [ResourceController::class, 'show']);
    Route::post('/resources', [ResourceController::class, 'store']);
    Route::put('/resources/{id}', [ResourceController::class, 'update']);
    Route::delete('/resources/{id}', [ResourceController::class, 'destroy']);

    Route::get('/resources/{resource}/comments', [CommentController::class, 'index']);
    Route::post('/resources/{resource}/comments', [CommentController::class, 'store']);

    Route::put('/resources/{resource}/comments/{comment}', [CommentController::class, 'update']);
    Route::get('/resources/{resource}/comments/{comment}', [CommentController::class, 'show']);
    Route::delete('/resources/{resource}/comments/{comment}', [CommentController::class, 'destroy']);

    Route::resource('resources', ResourceController::class);
});

Route::get('/test', function () {

});
