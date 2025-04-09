<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClassController extends Controller
{
  /**
   * Display a listing of classes assigned to the authenticated student.
   * Only for authenticated students who have been assigned to a class.
   */
  public function index()
  {
    try {
      $user = auth()->user();

      if (!$user || !$user->student) {
        return response()->failed([
          'status' => 'error',
          'message' => 'User is not associated with a student account',
        ], 403);
      }

      $studentId = $user->student->id;

      $classes = ClassModel::with(['studyYear'])
        ->whereHas('classHistories', function ($query) use ($studentId) {
          $query->where('student_id', $studentId);
        })
        ->get();

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
   * Only for authenticated students who have been assigned to a class.
   * This method retrieves the class details for the authenticated student.
   * @param string $id The ID of the class to retrieve.
   */
  public function show(string $id)
  {
    try {
      $user = auth()->user();

      if (!$user || !$user->student) {
        return response()->failed([
          'status' => 'error',
          'message' => 'User is not associated with a student account',
        ], 403);
      }

      $studentId = $user->student->id;

      $class = ClassModel::with(['studyYear'])
        ->whereHas('classHistories', function ($query) use ($studentId) {
          $query->where('student_id', $studentId);
        })
        ->findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $class,
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Class not found or not accessible',
      ], 404);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
