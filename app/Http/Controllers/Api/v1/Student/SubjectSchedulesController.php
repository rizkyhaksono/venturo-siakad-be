<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectSchedulesRequest;
use App\Models\SubjectSchedulesModel;
use App\Models\StudentModel;
use Exception;

class SubjectSchedulesController extends Controller
{
  /**
   * Display a listing of the resource.
   * Only for authenticated students who have been assigned to a class.
   * This method retrieves the subject schedules for the authenticated student.
   */
  public function index()
  {
    try {
      $student = StudentModel::with(['class'])->where('user_id', auth()->user()->id)->firstOrFail();
      $subjectSchedules = SubjectSchedulesModel::with(['class', 'subject', 'teacher', 'subjectHour'])
        ->where('class_id', $student->class->id)
        ->get();

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
      $student = StudentModel::with(['class'])->where('user_id', auth()->user()->id)->firstOrFail();
      $subjectSchedule = SubjectSchedulesModel::with(['class', 'subject', 'teacher', 'subjectHour'])
        ->where('class_id', $student->class->id)
        ->findOrFail($id);

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
