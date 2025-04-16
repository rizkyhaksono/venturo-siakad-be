<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\{
  ClassController,
  ClassHistoryController,
  HomeroomTeacherController,
  RegistrationController,
  StudentController,
  StudyYearController,
  SubjectController,
  SubjectHourController,
  SubjectScheduleController,
  TeacherController,
  UserController,
  UserRoleController,
};

Route::middleware(['auth.api', 'role:admin'])->group(function () {
  Route::get('registrations', [RegistrationController::class, 'index']);
  Route::put('registration/{id}', [RegistrationController::class, 'updateRegistrationStatus']);

  Route::apiResource('classes', ClassController::class);
  Route::apiResource('class-histories', ClassHistoryController::class);
  Route::apiResource('homeroom-teachers', HomeroomTeacherController::class);
  Route::apiResource('students', StudentController::class);
  Route::apiResource('study-years', StudyYearController::class);
  Route::apiResource('subjects', SubjectController::class);

  Route::apiResource('subject-hours', SubjectHourController::class);
  Route::get('subject-hours/restore/{id}', [SubjectHourController::class, 'restore']);

  Route::apiResource('subject-schedules', SubjectScheduleController::class);
  Route::apiResource('teachers', TeacherController::class);
  Route::apiResource('users', UserController::class);
  Route::apiResource('roles', UserRoleController::class);
});
