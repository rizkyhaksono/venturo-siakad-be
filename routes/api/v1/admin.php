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
    SubjectHoursController,
    SubjectSchedulesController,
    SubjectsController,
    UsersController,
    TeachersController
};

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected admin routes
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Auth management
    Route::post('logout', [AuthController::class, 'logout']);

    // User management
    Route::get('users/me', [UsersController::class, 'me']);
    Route::apiResource('users', UsersController::class);
    Route::post('users/{id}/restore', [UsersController::class, 'restore']);
    Route::put('users/{id}/change-role', [UsersController::class, 'changeRole']);

    // Teacher management
    Route::apiResource('teachers', TeachersController::class);

    // Registration management
    Route::put('registrations/{id}', [RegistrationController::class, 'updateRegistrationStatus']);

    // Resource routes
    Route::apiResource('classes', ClassesController::class);
    Route::post('classes/{id}/restore', [ClassesController::class, 'restore']);

    Route::apiResource('user-roles', UserRoleController::class);
    Route::post('user-roles/{id}/restore', [UserRoleController::class, 'restore']);

    Route::apiResource('class-histories', ClassHistoriesController::class);
    Route::post('class-histories/{id}/restore', [ClassHistoriesController::class, 'restore']);

    Route::apiResource('homeroom-teachers', HomeroomTeachersController::class);
    Route::post('homeroom-teachers/{id}/restore', [HomeroomTeachersController::class, 'restore']);

    Route::apiResource('students', StudentsController::class);
    Route::post('students/{id}/restore', [StudentsController::class, 'restore']);

    Route::apiResource('study-years', StudyYearController::class);
    Route::post('study-years/{id}/restore', [StudyYearController::class, 'restore']);

    Route::apiResource('subject-hours', SubjectHoursController::class);
    Route::post('subject-hours/{id}/restore', [SubjectHoursController::class, 'restore']);

    Route::apiResource('subject-schedules', SubjectSchedulesController::class);
    Route::post('subject-schedules/{id}/restore', [SubjectSchedulesController::class, 'restore']);

    Route::apiResource('subjects', SubjectsController::class);
    Route::post('subjects/{id}/restore', [SubjectsController::class, 'restore']);
});
