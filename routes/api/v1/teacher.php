<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Teacher\{
  ClassController,
  StudentController,
  SubjectController,
};

Route::middleware(['auth.api', 'role:teacher|admin'])->group(function () {
  Route::apiResource('classes', ClassController::class);
  Route::apiResource('students', StudentController::class);
  Route::apiResource('subjects', SubjectController::class);
});
