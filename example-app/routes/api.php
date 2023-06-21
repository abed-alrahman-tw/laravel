<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TeacherController;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Api;

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


Route::prefix('')->middleware('auth:api-admin')->group(function () {
    
    Route::apiResource('school',SchoolController::class);
    Route::apiResource('teacher',TeacherController::class);
    Route::get('logout',[ApiAuthController::class , 'logout']);


});

Route::post('register',[ApiAuthController::class , 'register']);
Route::post('login',[ApiAuthController::class , 'login']);



