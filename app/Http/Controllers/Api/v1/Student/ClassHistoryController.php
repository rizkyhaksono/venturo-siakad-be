<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\ClassHistoryResource;
use App\Models\ClassHistoryModel;

class ClassHistoryController extends Controller
{
  /**
   * Display a listing of the class history for the authenticated student.
   * Where the class history includes the class name, study year, and the number of students in each class.
   */
  public function index()
  {
    $user = auth()->user();

    if (!$user || !$user->student) {
      return response()->failed([
        'status' => 'error',
        'message' => 'User is not associated with a student account',
      ], 403);
    }

    $studentId = $user->student->id;

    $classHistories = ClassHistoryModel::with(['class', 'studyYear'])
      ->where('student_id', $studentId)
      ->get();

    return response()->json([
      'status' => true,
      'data' => ClassHistoryResource::collection($classHistories),
    ]);
  }
}
