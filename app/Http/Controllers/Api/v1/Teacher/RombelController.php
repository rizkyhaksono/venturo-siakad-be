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

  /**
   * Show all students in a specific rombel name.
   */
  public function showStudentsByRombelName(string $name)
  {
    $rombels = RombelModel::where('name', $name)
      ->with('student', 'class', 'studyYear', 'teacher')
      ->get();

    if ($rombels->isEmpty()) {
      return response()->json([
        'status' => 'error',
        'message' => "No rombel found with name '$name'",
        'data' => []
      ], 404);
    }

    $students = [];
    $rombelDetails = null;

    foreach ($rombels as $rombel) {
      $rombelData = $rombel->toArray();
      if (!$rombelDetails) {
        $rombelDetails = [
          'name' => $rombel->name,
          'class' => $rombelData['class'] ?? null,
          'study_year' => $rombelData['study_year'] ?? null,
          'teacher' => $rombelData['teacher'] ?? null
        ];
      }

      if (isset($rombelData['student'])) {
        $students[] = $rombelData['student'];
      }
    }

    return response()->json([
      'status' => 'success',
      'message' => "Students in rombel '$name' retrieved successfully",
      'data' => [
        'rombel' => $rombelDetails,
        'students' => $students
      ]
    ]);
  }
}
