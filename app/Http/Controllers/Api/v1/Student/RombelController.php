<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Models\RombelModel;

class RombelController extends Controller
{
  /**
   * Display a listing of the resource.
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

    $rombels = RombelModel::with([
      'class',
      'studyYear',
      'teacher',
      'student',
      'subjectSchedules'
    ])
      ->whereHas('student', function ($query) use ($studentId) {
        $query->where('student_id', $studentId);
      })
      ->get();

    return response()->json([
      'status' => 'success',
      'message' => 'Rombel list retrieved successfully',
      'data' => $rombels
    ]);
  }
}
