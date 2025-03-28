<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\{
    AuthController,
    ClassesController,
    UserRoleController,
    RegistrationController,
    ClassHistoriesController,
    HomeroomTeachersController,
    StudentsController,
    StudyYearController,
    SubjectHoursController
};

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Registration management
    Route::put('registrations/{id}', [RegistrationController::class, 'updateRegistrationStatus']);

    // Resource routes
    Route::apiResource('classes', ClassesController::class);
    Route::post('classes/{id}/restore', [ClassesController::class, 'restore']);

    Route::apiResource('user-roles', UserRoleController::class);

    Route::apiResource('class-histories', ClassHistoriesController::class);
    Route::post('class-histories/{id}/restore', [ClassHistoriesController::class, 'restore']);

    Route::apiResource('homeroom-teachers', HomeroomTeachersController::class);
    Route::post('homeroom-teachers/{id}/restore', [HomeroomTeachersController::class, 'restore']);

    Route::apiResource('students', StudentsController::class);
    Route::post('students/{id}/restore', [StudentsController::class, 'restore']);

    Route::apiResource('study-years', StudyYearController::class);
    Route::post('study-years/{id}/restore', [StudyYearController::class, 'restore']);
});
