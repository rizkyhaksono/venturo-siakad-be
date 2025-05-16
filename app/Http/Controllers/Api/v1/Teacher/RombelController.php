<?php

namespace App\Http\Controllers\Api\v1\Teacher;

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

    if (!$user || !$user->teacher) {
      return response()->failed([
        'status' => 'error',
        'message' => 'User is not associated with a teacher account',
      ], 403);
    }

    $teacherId = $user->teacher->id;

    $rombels = RombelModel::with([
      'class',
      'studyYear',
      'teacher',
      'student',
      'subjectSchedules'
    ])
      ->whereHas('teacher', function ($query) use ($teacherId) {
        $query->where('teacher_id', $teacherId);
      })
      ->get();

    return response()->json([
      'status' => 'success',
      'message' => 'Rombel list retrieved successfully',
      'data' => $rombels
    ]);
  }
}
