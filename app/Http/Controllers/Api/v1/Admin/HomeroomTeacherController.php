<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeroomTeacherRequest;
use App\Http\Resources\Admin\HomeroomTeacherResource;
use App\Models\HomeroomTeacherModel;
use Exception;

class HomeroomTeacherController extends Controller
{
  /**
   * Display a listing of homeroom teachers.
   */
  public function index()
  {
    try {
      $homeroomTeachers = HomeroomTeacherModel::with(['teacher', 'class', 'studyYear'])
        ->latest()
        ->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => HomeroomTeacherResource::collection($homeroomTeachers),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve homeroom teachers',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created homeroom teacher.
   */
  public function store(HomeroomTeacherRequest $request)
  {
    try {
      $validated = $request->validated();
      $homeroomTeacher = HomeroomTeacherModel::create($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Homeroom teacher created successfully',
        'data' => $homeroomTeacher->load(['teacher', 'class']),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified homeroom teacher.
   */
  public function show($id)
  {
    try {
      $homeroomTeacher = HomeroomTeacherModel::with(['teacher', 'class', 'studyYear'])
        ->findOrFail($id);

      return response()->json([
        'status' => 'success',
        'data' => new HomeroomTeacherResource($homeroomTeacher),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified homeroom teacher.
   */
  public function update(HomeroomTeacherRequest $request, HomeroomTeacherModel $homeroomTeacher)
  {
    try {
      $validated = $request->validated();
      $homeroomTeacher->update($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Homeroom teacher updated successfully',
        'data' => $homeroomTeacher->load(['teacher', 'class']),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified homeroom teacher.
   */
  public function destroy(HomeroomTeacherModel $homeroomTeacher)
  {
    try {
      $homeroomTeacher->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Homeroom teacher deleted successfully',
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore a soft-deleted homeroom teacher.
   */
  public function restore(int $id)
  {
    try {
      $homeroomTeacher = HomeroomTeacherModel::onlyTrashed()->find($id);
      $homeroomTeacher->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Homeroom teacher restored successfully',
        'data' => $homeroomTeacher->load(['teacher', 'class']),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore homeroom teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
