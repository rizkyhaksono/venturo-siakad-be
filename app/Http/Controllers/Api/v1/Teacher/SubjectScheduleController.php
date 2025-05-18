<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\SubjectScheduleResource;
use App\Models\SubjectScheduleModel;
use Exception;

class SubjectScheduleController extends Controller
{
  /**
   * Display a listing of the resource.
   * Only for authenticated teachers who have been assigned to a class.
   * This method retrieves the subject schedules for the authenticated teacher,
   * relations with teacher, homeroom teacher, and subject hour are included.
   */
  public function index()
  {
    try {
      $user = auth()->user();

      if (!$user || !$user->teacher) {
        return response()->failed([
          'status' => 'error',
          'message' => 'User is not associated with a teacher account',
        ], 403);
      }

      $teacherId = $user->teacher->id;

      $subjectSchedules = SubjectScheduleModel::with(['class', 'subject', 'teacher', 'subjectHour'])
        ->where('teacher_id', $teacherId)
        ->paginate(10);

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
}
