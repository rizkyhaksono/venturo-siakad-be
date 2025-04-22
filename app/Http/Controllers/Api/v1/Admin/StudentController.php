<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\Admin\StudentResource;
use App\Models\StudentModel;
use Exception;

class StudentController extends Controller
{
  /**
   * Display a listing of students.
   */
  public function index()
  {
    try {
      $students = StudentModel::with(['user', 'classHistories'])
        ->latest()
        ->paginate(10);

      return response()->json([
        'data' => StudentResource::collection($students),
        'meta' => [
          'current_page' => $students->currentPage(),
          'last_page' => $students->lastPage(),
          'per_page' => $students->perPage(),
          'total' => $students->total(),
        ],
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve students',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created student.
   */
  public function store(StudentRequest $request)
  {
    try {
      $validated = $request->validated();
      $student = StudentModel::create($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Student created successfully',
        'data' => $student->load(['user', 'classHistories']),
      ], 201);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create student',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified student.
   */
  public function show(string $id)
  {
    try {
      $student = StudentModel::with(['user',  'classHistories'])->findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $student,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve student',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified student.
   */
  public function update(StudentRequest $request, string $id)
  {
    try {
      $validated = $request->validated();
      $student = StudentModel::findOrFail($id);
      $student->update($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Student updated successfully',
        'data' => $student->load(['user', 'classHistories']),
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update student',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified student.
   */
  public function destroy(string $id)
  {
    try {
      $student = StudentModel::findOrFail($id);
      $student->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Student deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete student',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore the specified student.
   */
  public function restore(string $id)
  {
    try {
      $student = StudentModel::withTrashed()->findOrFail($id);
      $student->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Student restored successfully',
        'data' => $student->load(['user', 'classHistories']),
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore student',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
