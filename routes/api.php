<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\VolunteerController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\CertificateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('v1')->group(function () {
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('volunteers', VolunteerController::class);
    Route::get('certificate/verify/{volunteerId}', [CertificateController::class, 'verify']);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('events', EventController::class);
    Route::get('dashboard', [DashboardController::class, 'index']);
});
