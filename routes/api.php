<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\TriviaController as UserTriviaController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/user/trivia/questions', [UserTriviaController::class, 'getQuestions']);
Route::post('/user/trivia/submit', [UserTriviaController::class, 'submitAnswers']);
// Route::middleware('auth:sanctum')->get('/user/trivia/questions', [UserTriviaController::class, 'getQuestions']);
// Route::get('/user/trivia/questions', [UserTriviaController::class, 'getQuestions']);

