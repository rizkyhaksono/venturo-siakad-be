<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Models\SubjectScheduleModel;
use App\Http\Resources\Student\SubjectScheduleResource;
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
      $subjectSchedules = SubjectScheduleModel::with(['class', 'subject', 'teacher', 'subjectHour'])->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => SubjectScheduleResource::collection($subjectSchedules),
        'meta' => [
          'current_page' => $subjectSchedules->currentPage(),
          'last_page' => $subjectSchedules->lastPage(),
          'per_page' => $subjectSchedules->perPage(),
          'total' => $subjectSchedules->total(),
        ],
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

      return response()->json([
        'status' => 'success',
        'data' => new SubjectScheduleResource($subjectSchedule),
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
