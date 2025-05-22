<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Student\{
  ClassController,
  ClassHistoryController,
  SubjectScheduleController,
  RombelController,
  StudentAssesmentController,
  SPPController,
  SPPHistoryController,
};


Route::middleware(['auth.api', 'role:student|admin'])->group(function () {
  Route::apiResource('classes', ClassController::class);
  Route::apiResource('class-histories', ClassHistoryController::class);
  Route::apiResource('subject-schedules', SubjectScheduleController::class);
  Route::apiResource('rombels', RombelController::class);
  Route::get('rombels/{rombelId}/schedule', [RombelController::class, 'showSchedule']);
  Route::apiResource('student-assesments', StudentAssesmentController::class);
  Route::apiResource('spp', SPPController::class);
  Route::apiResource('spp-history', SPPHistoryController::class);
});
