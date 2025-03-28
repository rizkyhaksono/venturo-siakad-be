<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassesRequest;
use App\Models\ClassesModel;
use Exception;

class ClassesController extends Controller
{
  /**
   * Display a listing of classes.
   */
  public function index()
  {
    try {
      $classes = ClassesModel::with(['studyYear'])
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
   * Store a newly created class.
   */
  public function store(ClassesRequest $request)
  {
    try {
      $validated = $request->validated();
      $class = ClassesModel::create($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Class created successfully',
        'data' => $class->load('studyYear'),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified class.
   */
  public function show(ClassesModel $class)
  {
    try {
      return response()->success([
        'status' => 'success',
        'data' => $class->load([
          'studyYear',
          'subjects',
          'classHistories',
          'subjectSchedules',
          'homeroomTeachers'
        ]),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified class.
   */
  public function update(ClassesRequest $request, ClassesModel $class)
  {
    try {
      $validated = $request->validated();
      $class->update($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Class updated successfully',
        'data' => $class->load('studyYear'),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified class (soft delete).
   */
  public function destroy(ClassesModel $class)
  {
    try {
      $class->update(['deleted_by' => auth()->id()]);
      $class->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Class deleted successfully',
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore a soft-deleted class.
   */
  public function restore(string $id)
  {
    try {
      $class = ClassesModel::withTrashed()->findOrFail($id);
      $class->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Class restored successfully',
        'data' => $class->load('studyYear'),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore class',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
