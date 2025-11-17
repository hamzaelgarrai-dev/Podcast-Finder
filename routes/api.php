<?php

use App\Http\Controllers\AnimateurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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

Route::post('register', [AuthController::class , 'register']);
Route::post('login', [AuthController::class , 'login']);
Route::post('logout', [AuthController::class , 'logout'])->middleware('auth:sanctum');
Route::post('password/forget',[AuthController::class, 'forgetPassword']);
Route::post('password/reset',[AuthController::class, 'resetPassword']);

//users Routes

Route::middleware('auth:sanctum')->group(function () {

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});




//podcasts Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('podcasts', [PodcastController::class , 'store']);
    Route::put('podcasts/{id}', [PodcastController::class , 'update']);
    Route::delete('podcasts/{id}', [PodcastController::class , 'destroy']);
    Route::get('podcasts', [PodcastController::class , 'index']);
   Route::get('podcasts/{id}', [PodcastController::class , 'show']);
});



//episodes Routes

Route::middleware('auth:sanctum')->group(function () {
    Route::post('podcasts/{id}/episodes', [EpisodeController::class, 'store']);
    Route::put('episodes/{id}', [EpisodeController::class, 'update']);
    Route::delete('episodes/{id}', [EpisodeController::class, 'destroy']);
});

Route::get('podcasts/{id}/episodes', [EpisodeController::class, 'index']);
Route::get('episodes/{id}', [EpisodeController::class, 'show']);

//Animateur Routes


Route::middleware('auth:sanctum')->group(function () {

    Route::post('hosts' ,[AnimateurController::class , 'store']);
    Route::put('hosts/{id}' ,[AnimateurController::class , 'update']);
    Route::delete('hosts/{id}' ,[AnimateurController::class , 'destroy']);
});

    Route::get('hosts' ,[AnimateurController::class , 'index']);
    Route::get('hosts/{id}' ,[AnimateurController::class , 'show']);

//search Routes

Route::get('search/podcasts' , [SearchController::class,'SearchPodcasts']);
Route::get('search/episodes' , [SearchController::class,'SearchEpisodes']);