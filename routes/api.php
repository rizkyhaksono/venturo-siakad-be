<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Api\v1\Admin\AuthController;
use App\Http\Controllers\Api\v1\Admin\ClassesController;
use App\Http\Controllers\Api\v1\Admin\UserRoleController;
use App\Http\Controllers\Api\v1\Admin\RegistrationController;
use App\Http\Controllers\Api\v1\Admin\ClassHistoriesController;

// Student Controllers
use App\Http\Controllers\Api\v1\Student\ClassesController as StudentClassesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api;" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

Route::prefix('v1/admin')->group(function () {
    // Public routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Protected admin routes
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);

        // Registration management
        Route::put('registrations/{id}', [RegistrationController::class, 'updateRegistrationStatus']);

        // Classes management
        Route::prefix('classes')->group(function () {
            Route::get('/', [ClassesController::class, 'index']);
            Route::post('/', [ClassesController::class, 'store']);
            Route::get('/{class}', [ClassesController::class, 'show']);
            Route::put('/{class}', [ClassesController::class, 'update']);
            Route::delete('/{class}', [ClassesController::class, 'destroy']);
            Route::post('/restore/{id}', [ClassesController::class, 'restore']);
        });

        // User roles management
        Route::prefix('user-roles')->group(function () {
            Route::get('/', [UserRoleController::class, 'index']);
            Route::post('/', [UserRoleController::class, 'store']);
            Route::get('/{id}', [UserRoleController::class, 'show']);
            Route::put('/{id}', [UserRoleController::class, 'update']);
            Route::delete('/{id}', [UserRoleController::class, 'destroy']);
        });

        // Class histories management
        Route::prefix('class-histories')->group(function () {
            Route::get('/', [ClassHistoriesController::class, 'index']);
            Route::post('/', [ClassHistoriesController::class, 'store']);
            Route::get('/{id}', [ClassHistoriesController::class, 'show']);
            Route::put('/{id}', [ClassHistoriesController::class, 'update']);
            Route::delete('/{id}', [ClassHistoriesController::class, 'destroy']);
            Route::post('/restore/{id}', [ClassHistoriesController::class, 'restore']);
        });
    });
});

Route::prefix('v1/student')->group(function () {
    // Public routes
    // Route::post('register', [AuthController::class, 'register']);
    // Route::post('login', [AuthController::class, 'login']);

    // Protected student routes
    Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
        // Route::post('logout', [AuthController::class, 'logout']);
        // Route::get('me', [AuthController::class, 'me']);

        // Classes management
        Route::prefix('classes')->group(function () {
            Route::get('/', [StudentClassesController::class, 'index']);
            Route::get('/{class}', [StudentClassesController::class, 'show']);
        });
    });
});
