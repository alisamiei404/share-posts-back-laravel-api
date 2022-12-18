<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminPostController;

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

// Route::middleware('auth:sanctum')->post('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/posts', [PostController::class, 'getAllPosts']);
Route::get('/posts/{slug}', [PostController::class, 'getPost']);
Route::get('/ad/log1', [AdminDashboardController::class, 'getAllRequest1']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/checkAdmin', [AuthController::class, 'checkAdmin']);
    
    Route::get('/myposts', [PostController::class, 'getAllMyPosts']);
    Route::post('/posts', [PostController::class, 'createPost']);
    Route::delete('/posts', [PostController::class, 'deleteAllPosts']);
    Route::delete('/posts/{slug}', [PostController::class, 'deletePost']);
    Route::post('/posts/{slug}', [PostController::class, 'editPost']);
    Route::put('/posts/{slug}', [PostController::class, 'updatePost']);
    Route::get('/posts/search', [PostController::class, 'getSearch']);
});

// Route::middleware('auth:sanctum')->get('/posts', [PostController::class, 'getAllPosts']);


Route::prefix('admin')->middleware(['auth:sanctum','admin'])->group(function () {
    Route::get('/logLogin', [AdminDashboardController::class, 'getAllLogin']);
    Route::get('/logRequest', [AdminDashboardController::class, 'getAllRequest']);

    Route::get('/users', [AdminUserController::class, 'getAllUsers']);
    Route::put('/users/updateStatus/{slug}', [AdminUserController::class, 'updateStatusUser']);
    Route::delete('/users/{slug}', [AdminUserController::class, 'deleteUser']);

    Route::get('/posts', [AdminPostController::class, 'getAllPosts']);
    Route::get('/postsUser', [AdminPostController::class, 'getAllPostsUser']);
    Route::get('/posts/{slug}', [AdminPostController::class, 'getPost']);

    Route::put('/posts/updateStatus', [AdminPostController::class, 'updateStatusPost']);
    Route::delete('/posts/{slug}', [AdminPostController::class, 'deletePost']);
});

// Route::middleware('auth:sanctum')->post('/posts', [PostController::class, 'createPost']);

