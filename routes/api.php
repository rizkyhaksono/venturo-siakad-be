<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Api\v1\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\v1\Admin\ClassesController as AdminClassesController;
use App\Http\Controllers\Api\v1\Admin\UserRoleController as AdminUserRoleController;
use App\Http\Controllers\Api\v1\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Api\v1\Admin\ClassHistoriesController as AdminClassHistoriesController;
use App\Http\Controllers\Api\v1\Admin\HomeroomTeachersController as AdminHomeroomTeachersController;
use App\Http\Controllers\Api\v1\Admin\StudentsController as AdminStudentController;

// Student Controllers
use App\Http\Controllers\Api\v1\Student\ClassesController as StudentClassesController;

// Teacher Controllers

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

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Siakad API Documentation"
 * )
 */

/**
 * Admin Routes
 * @OA\Tag(
 *     name="Admin",
 *     description="API Endpoints for Admin"
 * )
 */
Route::prefix('v1/admin')->group(function () {
    // Public routes
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    // Protected admin routes
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        Route::get('me', [AdminAuthController::class, 'me']);

        // Registration management
        Route::put('registrations/{id}', [AdminRegistrationController::class, 'updateRegistrationStatus']);

        // Classes management
        Route::prefix('classes')->group(function () {
            Route::get('/', [AdminClassesController::class, 'index']);
            Route::post('/', [AdminClassesController::class, 'store']);
            Route::get('/{class}', [AdminClassesController::class, 'show']);
            Route::put('/{class}', [AdminClassesController::class, 'update']);
            Route::delete('/{class}', [AdminClassesController::class, 'destroy']);
            Route::post('/restore/{id}', [AdminClassesController::class, 'restore']);
        });

        // User roles management
        Route::prefix('user-roles')->group(function () {
            Route::get('/', [AdminUserRoleController::class, 'index']);
            Route::post('/', [AdminUserRoleController::class, 'store']);
            Route::get('/{id}', [AdminUserRoleController::class, 'show']);
            Route::put('/{id}', [AdminUserRoleController::class, 'update']);
            Route::delete('/{id}', [AdminUserRoleController::class, 'destroy']);
        });

        // Class histories management
        Route::prefix('class-histories')->group(function () {
            Route::get('/', [AdminClassHistoriesController::class, 'index']);
            Route::post('/', [AdminClassHistoriesController::class, 'store']);
            Route::get('/{id}', [AdminClassHistoriesController::class, 'show']);
            Route::put('/{id}', [AdminClassHistoriesController::class, 'update']);
            Route::delete('/{id}', [AdminClassHistoriesController::class, 'destroy']);
            Route::post('/restore/{id}', [AdminClassHistoriesController::class, 'restore']);
        });

        // Homeroom teachers management
        Route::prefix('homeroom-teachers')->group(function () {
            Route::get('/', [AdminHomeroomTeachersController::class, 'index']);
            Route::post('/', [AdminHomeroomTeachersController::class, 'store']);
            Route::get('/{id}', [AdminHomeroomTeachersController::class, 'show']);
            Route::put('/{id}', [AdminHomeroomTeachersController::class, 'update']);
            Route::delete('/{id}', [AdminHomeroomTeachersController::class, 'destroy']);
            Route::post('/restore/{id}', [AdminHomeroomTeachersController::class, 'restore']);
        });

        // Students management
        Route::prefix('students')->group(function () {
            Route::get('/', [AdminStudentController::class, 'index']);
            Route::post('/', [AdminStudentController::class, 'store']);
            Route::get('/{student}', [AdminStudentController::class, 'show']);
            Route::put('/{student}', [AdminStudentController::class, 'update']);
            Route::delete('/{student}', [AdminStudentController::class, 'destroy']);
            Route::post('/restore/{id}', [AdminStudentController::class, 'restore']);
        });
    });
});

/**
 * Student Routes
 * @OA\Tag(
 *     name="Student",
 *     description="API Endpoints for Students"
 * )
 */
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
