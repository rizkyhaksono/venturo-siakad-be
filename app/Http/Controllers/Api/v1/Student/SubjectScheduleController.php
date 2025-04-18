<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Models\SubjectScheduleModel;
use App\Models\StudentModel;
use Exception;

class SubjectScheduleController extends Controller
{
  /**
   * Display a listing of the resource.
   * Only for authenticated students who have been assigned to a class.
   * This method retrieves the subject schedules for the authenticated student,
   * relations with teacher, homeroom teacher, and subject hour are included.
   */
  public function index()
  {
    try {
      $subjectSchedules = SubjectScheduleModel::with(['class', 'subject', 'teacher', 'subjectHour'])->get();

      return response()->success([
        'status' => 'success',
        'data' => $subjectSchedules,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject schedules',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   * Only for authenticated students who have been assigned to a class.
   */
  public function show(string $id)
  {
    try {
      $subjectSchedule = SubjectScheduleModel::with(['class', 'subject', 'teacher', 'subjectHour'])->findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $subjectSchedule,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject schedule',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
