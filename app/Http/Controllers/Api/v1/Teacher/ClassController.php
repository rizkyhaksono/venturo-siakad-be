<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Exception;

class ClassController extends Controller
{
  /**
   * This method is used to retrieve the list of classes for the authenticated teacher.
   * It includes the class name, homeroom teacher, and the number of students in each class.
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

      $classes = ClassModel::with(['studyYear'])
        ->whereHas('homeroomTeachers', function ($query) use ($teacherId) {
          $query->where('teacher_id', $teacherId);
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
   * Only for authenticated teachers who have been assigned to a class.
   * This method retrieves the class details for
   * the authenticated teacher.
   * @param string $id The ID of the class to retrieve.
   */
  public function show(string $id)
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

      $class = ClassModel::with(['studyYear'])
        ->whereHas('homeroomTeachers', function ($query) use ($teacherId) {
          $query->where('teacher_id', $teacherId);
        })
        ->findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $class,
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Class not found',
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
