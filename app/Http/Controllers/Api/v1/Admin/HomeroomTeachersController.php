<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeroomTeachersRequest;
use App\Models\HomeroomTeachersModel;
use Illuminate\Http\JsonResponse;
use Exception;

class HomeroomTeachersController extends Controller
{
  /**
   * Display a listing of homeroom teachers.
   */
  public function index(): JsonResponse
  {
    try {
      $homeroomTeachers = HomeroomTeachersModel::with(['teacher', 'class'])
        ->latest()
        ->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => $homeroomTeachers,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to retrieve homeroom teachers',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created homeroom teacher.
   */
  public function store(HomeroomTeachersRequest $request): JsonResponse
  {
    try {
      $validated = $request->validated();
      $homeroomTeacher = HomeroomTeachersModel::create($validated);

      return response()->json([
        'status' => 'success',
        'message' => 'Homeroom teacher created successfully',
        'data' => $homeroomTeacher->load(['teacher', 'class']),
      ], 201);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to create homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified homeroom teacher.
   */
  public function show(HomeroomTeachersModel $class): JsonResponse
  {
    try {
      return response()->json([
        'status' => 'success',
        'data' => $class->load(['teacher', 'class']),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to retrieve homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified homeroom teacher.
   */
  public function update(HomeroomTeachersRequest $request, HomeroomTeachersModel $homeroomTeacher): JsonResponse
  {
    try {
      $validated = $request->validated();
      $homeroomTeacher->update($validated);

      return response()->json([
        'status' => 'success',
        'message' => 'Homeroom teacher updated successfully',
        'data' => $homeroomTeacher->load(['teacher', 'class']),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to update homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified homeroom teacher.
   */
  public function destroy(HomeroomTeachersModel $homeroomTeacher): JsonResponse
  {
    try {
      $homeroomTeacher->delete();

      return response()->json([
        'status' => 'success',
        'message' => 'Homeroom teacher deleted successfully',
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to delete homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore a soft-deleted homeroom teacher.
   */
  public function restore(int $id): JsonResponse
  {
    try {
      $homeroomTeacher = HomeroomTeachersModel::onlyTrashed()->find($id);
      $homeroomTeacher->restore();

      return response()->json([
        'status' => 'success',
        'message' => 'Homeroom teacher restored successfully',
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to restore homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
