<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ExploitedController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReadLaterController;
use App\Http\Controllers\Api\RelationController;
use App\Http\Controllers\Api\RelationRequestController;
use App\Http\Controllers\Api\RelationTypeController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/* --- Not connected citizen --- */
Route::middleware('guest')->group(function () {

    /* --- Authentication --- */
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [RegisterController::class, 'login']);
    Route::get('/login', [RegisterController::class, 'login_failed'])
        ->name('login_failed');

    /* --- Password --- */
    Route::post('/forgot-password', [PasswordResetController::class, 'store']);
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store']);

    /* --- Public Resources --- */
    Route::get('/public/resources', [ResourceController::class, 'index']);
    Route::get('/public/resources/{id}', [ResourceController::class, 'show']);
});

/* --- Connected citizen --- */
Route::middleware('auth:sanctum')->group(function () {

    /* --- Resources --- */
    Route::get('/resources', [ResourceController::class, 'index']);
    Route::get('/resources/{id}', [ResourceController::class, 'show']);

    Route::group(['middleware' => ['role:super-admin|admin|moderator|citizen']], function () {

        /* --- Users --- */
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);

        /* --- User Relation --- */
        Route::get('/users/{user}/relations', [RelationController::class, 'index']);
        Route::post('/users/{user}/relations', [RelationController::class, 'store']);
        Route::get('/users/{user}/relations/{id}', [RelationController::class, 'show']);
        Route::put('/users/{user}/relations/{id}', [RelationController::class, 'update']);
        Route::delete('/users/{user}/relations/{id}', [RelationController::class, 'destroy']);

        /* --- Relation Types --- ok */
        Route::get('/relation_types', [RelationTypeController::class, 'index']);
        Route::get('/relation_types/{id}', [RelationTypeController::class, 'show']);

        /* --- Resources --- ok */
        Route::get('/search/resources', [ResourceController::class, 'search']);
        Route::post('/resources', [ResourceController::class, 'store']);
        Route::put('/resources/{id}', [ResourceController::class, 'update']);
        Route::delete('/resources/{id}', [ResourceController::class, 'destroy']);

        /* --- Favorites Resources --- */
        Route::get('/users/{user}/favorites', [FavoriteController::class, 'index']);
        Route::post('/users/{user}/favorites/{resource}', [FavoriteController::class, 'store']);
        Route::delete('/users/{user}/favorites/{resource}', [FavoriteController::class, 'destroy']);

        /* --- Read later Resources --- */
        Route::get('/users/{user}/read_later', [ReadLaterController::class, 'index']);
        Route::post('/users/{user}/read_later/{resource}', [ReadLaterController::class, 'store']);
        Route::delete('/users/{user}/read_later/{resource}', [ReadLaterController::class, 'destroy']);

        /* --- Exploited Resources --- */
        Route::get('/users/{user}/exploited', [ExploitedController::class, 'index']);
        Route::post('/users/{user}/exploited/{resource}', [ExploitedController::class, 'store']);
        Route::delete('/users/{user}/exploited/{resource}', [ExploitedController::class, 'destroy']);

        /* --- Resource Comments --- */
        Route::get('/resources/{resource}/comments', [CommentController::class, 'index']);
        Route::post('/resources/{resource}/comments', [CommentController::class, 'store']);
        Route::put('/resources/{resource}/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/resources/{resource}/comments/{comment}', [CommentController::class, 'destroy']);

        /* --- Resource Types --- */
        Route::get('/types', [TypeController::class, 'index']);
        Route::get('/types/{id}', [TypeController::class, 'show']);

        /* --- Categories --- */
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
    });

    Route::group(['middleware' => ['role:super-admin|admin']], function () {
        /* --- Users --- */
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        /* --- Relation Types --- */
        Route::post('/relation_types', [RelationTypeController::class, 'store']);
        Route::put('/relation_types/{id}', [RelationTypeController::class, 'update']);
        Route::delete('/relation_types/{id}', [RelationTypeController::class, 'destroy']);

        /* --- Resource Types --- */
        Route::post('/types', [TypeController::class, 'store']);
        Route::put('/types/{id}', [TypeController::class, 'update']);
        Route::delete('/types/{id}', [TypeController::class, 'destroy']);

        /* --- Categories --- */
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    });

    Route::group(['middleware' => ['role:super-admin|admin']], function () {
        /* --- Users --- */
        Route::post('/users', [UserController::class, 'store']);
    });
});

Route::get('/test', function () {

});
