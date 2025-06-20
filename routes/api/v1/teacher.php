<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Teacher\{
  ClassController,
  StudentController,
  SubjectController,
  RombelController,
  SubjectScheduleController,
  StudentAssesmentController,
};

Route::middleware(['auth.api', 'role:teacher|admin', 'signature.verify', 'verify.signature'])->group(function () {
  Route::apiResource('classes', ClassController::class);
  Route::apiResource('students', StudentController::class);
  Route::apiResource('subjects', SubjectController::class);

  Route::apiResource('rombels', RombelController::class);
  Route::get('rombels/{rombelId}/schedule', [RombelController::class, 'showSchedule']);
  Route::get('rombels/{name}/students', [RombelController::class, 'showStudentsByRombelName']);

  Route::apiResource('subject-schedules', SubjectScheduleController::class);
  Route::apiResource('student-assessments', StudentAssesmentController::class);
});
