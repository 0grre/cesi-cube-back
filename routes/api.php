<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReadLaterController;
use App\Http\Controllers\Api\RelationController;
use App\Http\Controllers\Api\RelationRequestController;
use App\Http\Controllers\Api\RelationTypeController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Resources\UserResource;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/*
| Not connected citizen
*/
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);
Route::get('/login', [RegisterController::class, 'login_failed'])->name('login');

Route::get('/resources', [ResourceController::class, 'index']);
Route::get('/resources/{id}', [ResourceController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // à voir avec l'historie du guard

    /* --- Resources --- */
    Route::get('/resources', [ResourceController::class, 'index']);
    Route::get('/resources/{id}', [ResourceController::class, 'show']);

    Route::group(['middleware' => ['role:super-admin|admin|moderator|citizen']], function () {

        /* --- Users --- */
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);

        /* --- User Relation Requests --- */
        Route::get('/users/{user}/relation_requests', [RelationRequestController::class, 'index']);
        Route::post('/users/{user}/relation_requests', [RelationRequestController::class, 'store']);
        Route::get('/users/{user}/relation_requests/{id}', [RelationRequestController::class, 'show']);
        Route::put('/users/{user}/relation_requests/{relation_request}', [RelationRequestController::class, 'update']);
        Route::delete('/users/{user}/relation_requests/{id}', [RelationRequestController::class, 'destroy']);

        /* --- User Relation --- */
        Route::get('/users/{user}/relations', [RelationController::class, 'index']);
        Route::post('/users/{user}/relations', [RelationController::class, 'store']);
        Route::get('/users/{user}/relations/{id}', [RelationController::class, 'show']);
        Route::put('/users/{user}/relations/{relation}', [RelationController::class, 'update']);
        Route::delete('/users/{user}/relations/{id}', [RelationController::class, 'destroy']);

        /* --- Relation Types --- ok */
        Route::get('/relation_types', [RelationTypeController::class, 'index']);
        Route::get('/relation_types/{id}', [RelationTypeController::class, 'show']);

        /* --- Resources --- ok */
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

        /* --- Resource Comments --- */
        Route::get('/resources/{resource}/comments', [CommentController::class, 'index']);
        Route::post('/resources/{resource}/comments', [CommentController::class, 'store']);
        Route::get('/resources/{resource}/comments/{comment}', [CommentController::class, 'show']);
        Route::put('/resources/{resource}/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/resources/{resource}/comments/{comment}', [CommentController::class, 'destroy']);

        /* --- Resource Types --- */
        Route::get('/types', [TypeController::class, 'index']);
        Route::get('/types/{id}', [TypeController::class, 'show']);

        /* --- Categories --- */
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
    });

    Route::group(['middleware' => ['role:super-admin|admin|moderator']], function () {


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
});

Route::get('/test', function () {

    for ($i = 0; $i <= 25; $i++) {
        $user = User::inRandomOrder()->limit(1)->first();
        $resource = Resource::inRandomOrder()->limit(1)->first();

        if (!$user->exploited()->where('resource_id', $resource->id)->exists())
        {
            $user->exploited()->attach($resource);
        }
    }
});
