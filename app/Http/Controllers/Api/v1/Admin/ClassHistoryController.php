<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassHistoryRequest;
use App\Models\ClassHistoryModel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassHistoryController extends Controller
{
  /**
   * Display a listing of class histories.
   */
  public function index(Request $request)
  {
    try {
      $query = ClassHistoryModel::with(['student', 'class', 'studyYear']);

      if ($request->has('search')) {
        $query->whereHas('student', function ($q) use ($request) {
          $q->where('name', 'like', "%{$request->search}%");
        });
      }

      $classHistories = $query->paginate($request->input('per_page', 10));

      return response()->success([
        'status' => true,
        'data' => $classHistories
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->failed([
        'status' => false,
        'message' => 'Failed to fetch class histories',
        'error' => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Store a newly created class history.
   */
  public function store(ClassHistoryRequest $request)
  {
    try {
      $validated = $request->validated();
      $validated['created_by'] = auth()->id();

      $classHistory = ClassHistoryModel::create($validated);

      return response()->success([
        'status' => true,
        'message' => 'Class history created successfully',
        'data' => $classHistory->load(['student', 'class', 'studyYear'])
      ], Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return response()->failed([
        'status' => false,
        'message' => 'Failed to create class history',
        'error' => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Display the specified class history.
   */
  public function show($id)
  {
    try {
      $classHistory = ClassHistoryModel::with(['student', 'class', 'studyYear'])->findOrFail($id);

      return response()->success([
        'status' => true,
        'data' => $classHistory
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->failed([
        'status' => false,
        'message' => 'Class history not found',
        'error' => $e->getMessage()
      ], Response::HTTP_NOT_FOUND);
    }
  }

  /**
   * Update the specified class history.
   */
  public function update(ClassHistoryRequest $request, $id)
  {
    try {
      $validated = $request->validated();
      $validated['updated_by'] = auth()->id();

      $classHistory = ClassHistoryModel::findOrFail($id);
      $classHistory->update($validated);

      return response()->success([
        'status' => true,
        'message' => 'Class history updated successfully',
        'data' => $classHistory
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->failed([
        'status' => false,
        'message' => 'Failed to update class history',
        'error' => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Remove the specified class history.
   */
  public function destroy($id)
  {
    try {
      $classHistory = ClassHistoryModel::findOrFail($id);
      $classHistory->deleted_by = auth()->id();
      $classHistory->save();
      $classHistory->delete();

      return response()->success([
        'status' => true,
        'message' => 'Class history deleted successfully'
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->failed([
        'status' => false,
        'message' => 'Failed to delete class history',
        'error' => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  /**
   * Restore the specified class history.
   */
  public function restore($id): JsonResponse
  {
    try {
      $classHistory = ClassHistoryModel::withTrashed()->findOrFail($id);
      $classHistory->restore();

      return response()->success([
        'status' => true,
        'message' => 'Class history restored successfully',
        'data' => $classHistory
      ], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->failed([
        'status' => false,
        'message' => 'Failed to restore class history',
        'error' => $e->getMessage()
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}
