<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassHistoriesRequest;
use App\Models\ClassHistoriesModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ClassHistoriesController extends Controller
{
  /**
   * Display a listing of class histories.
   */
  public function index(Request $request)
  {
    try {
      $query = ClassHistoriesModel::with(['student', 'class', 'studyYear']);

      if ($request->has('search')) {
        $query->whereHas('student', function ($q) use ($request) {
          $q->where('name', 'like', "%{$request->search}%");
        });
      }

      $classHistories = $query->paginate($request->input('per_page', 10));

      return response()->json([
        'status' => true,
        'data' => $classHistories
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to fetch class histories'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Store a newly created class history.
   */
  public function store(ClassHistoriesRequest $request)
  {
    DB::beginTransaction();
    try {
      $validated = $request->validated();

      $classHistory = ClassHistoriesModel::create([
        'student_id' => $validated['student_id'],
        'class_id' => $validated['class_id'],
        'study_year_id' => $validated['study_year_id'],
        'previous_status' => $validated['previous_status'],
        'new_status' => $validated['new_status'],
        'entry_date' => $validated['entry_date'],
        'created_by' => auth()->id()
      ]);

      DB::commit();

      return response()->json([
        'status' => true,
        'message' => 'Class history created successfully',
        'data' => $classHistory
      ], Response::HTTP_CREATED);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => 'Failed to create class history'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Display the specified class history.
   */
  public function show($id)
  {
    try {
      $classHistory = ClassHistoriesModel::with(['student', 'class', 'studyYear'])->findOrFail($id);

      return response()->json([
        'status' => true,
        'data' => $classHistory
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Class history not found'
      ], Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * Update the specified class history.
   */
  public function update(ClassHistoriesRequest $request, $id)
  {
    DB::beginTransaction();
    try {
      $validated = $request->validated();

      $classHistory = ClassHistoriesModel::findOrFail($id);
      $classHistory->update([
        'student_id' => $validated['student_id'],
        'class_id' => $validated['class_id'],
        'study_year_id' => $validated['study_year_id'],
        'previous_status' => $validated['previous_status'],
        'new_status' => $validated['new_status'],
        'entry_date' => $validated['entry_date'],
        'updated_by' => auth()->id()
      ]);

      DB::commit();

      return response()->json([
        'status' => true,
        'message' => 'Class history updated successfully',
        'data' => $classHistory
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => 'Failed to update class history'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Remove the specified class history.
   */
  public function destroy($id)
  {
    DB::beginTransaction();
    try {
      $classHistory = ClassHistoriesModel::findOrFail($id);
      $classHistory->deleted_by = auth()->id();
      $classHistory->save();
      $classHistory->delete();

      DB::commit();

      return response()->json([
        'status' => true,
        'message' => 'Class history deleted successfully'
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => 'Failed to delete class history'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Restore the specified class history.
   */
  public function restore($id): JsonResponse
  {
    DB::beginTransaction();
    try {
      $classHistory = ClassHistoriesModel::withTrashed()->findOrFail($id);
      $classHistory->restore();

      DB::commit();

      return response()->json([
        'status' => true,
        'message' => 'Class history restored successfully',
        'data' => $classHistory
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => false,
        'message' => 'Failed to restore class history'
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
