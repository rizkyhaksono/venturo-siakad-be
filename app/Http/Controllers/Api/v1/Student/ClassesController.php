<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassesModel;

class ClassesController extends Controller
{
  /**
   * Display a listing of classes assigned to the authenticated student.
   */
  public function index()
  {
    try {
      $studentId = auth()->user()->student->id;

      $classes = ClassesModel::with(['studyYear'])
        ->whereHas('classHistories', function ($query) use ($studentId) {
          $query->where('student_id', $studentId);
        })
        ->latest()
        ->paginate(10);

      return response()->success([
        'status' => 'success',
        'data' => $classes,
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve classes',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified class.
   */
  public function show(string $id)
  {
    try {
      $studentId = auth()->user()->student->id;

      $class = ClassesModel::with(['studyYear'])
        ->whereHas('classHistories', function ($query) use ($studentId) {
          $query->where('student_id', $studentId);
        })
        ->find($id);

      if (!$class) {
        return response()->failed([
          'status' => 'failed',
          'message' => 'Kelas tidak ditemukan',
        ], 404);
      }

      return response()->success([
        'status' => 'success',
        'data' => $class,
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
