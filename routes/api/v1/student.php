<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Student\{
  ClassController,
  SubjectScheduleController
};


Route::middleware(['auth.api', 'role:student'])->group(function () {
  Route::apiResource('classes', ClassController::class);
  Route::apiResource('subject-schedules', SubjectScheduleController::class);
});
