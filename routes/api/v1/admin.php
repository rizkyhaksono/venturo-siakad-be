<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Admin\{
  ClassController,
  ClassHistoryController,
  HomeroomTeacherController,
  RegistrationController,
  RombelController,
  StudentController,
  StudyYearController,
  SubjectController,
  SubjectHourController,
  SubjectScheduleController,
  TeacherController,
  UserController,
  UserRoleController,
  KKMController,
  StudentAssesmentController,
  SPPController,
  SPPHistoryController,
};

// Route::middleware(['auth.api', 'role:admin', 'signature.verify', 'verify.signature'])->group(function () {
Route::middleware(['auth.api', 'role:admin'])->group(function () {
  Route::get('registrations', [RegistrationController::class, 'index']);
  Route::put('registration/{id}', [RegistrationController::class, 'updateRegistrationStatus']);

  Route::apiResource('classes', ClassController::class);
  Route::apiResource('class-histories', ClassHistoryController::class);

  Route::get('rombels/trashed', [RombelController::class, 'trashed']);
  Route::get('rombels/restore/{id}', [RombelController::class, 'restore']);
  Route::get('rombels/force-delete/{id}', [RombelController::class, 'forceDelete']);

  Route::apiResource('rombels', RombelController::class);
  Route::get('rombels/student/{classId}', [RombelController::class, 'showStudentsByRombelName']);

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

  Route::get('kkm/trashed', [KKMController::class, 'trashed']);
  Route::get('kkm/restore/{id}', [KKMController::class, 'restore']);
  Route::get('kkm/force-delete/{id}', [KKMController::class, 'forceDelete']);
  Route::apiResource('kkm', KKMController::class);

  Route::apiResource('student-assesments', StudentAssesmentController::class);
  Route::get('student-assesments/rombels', [StudentAssesmentController::class, 'studentAssessmentRombels']);
  Route::get('student-assesments/{studentId}/{studyYearId}', [StudentAssesmentController::class, 'studentAssessmentByStudentIdAndStudyYearId']);

  Route::apiResource('spp', SPPController::class);

  Route::apiResource('spp-history', SPPHistoryController::class);
  Route::get('spp-history/proof-payment/{id}', [SPPHistoryController::class, 'showProofPayment']);
});
