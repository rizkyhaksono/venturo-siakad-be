<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassHistoriesRequest;
use App\Models\ClassHistoriesModel;
use Illuminate\Http\Request;
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
  public function store(ClassHistoriesRequest $request)
  {
    try {
      $validated = $request->validated();
      $validated['created_by'] = auth()->id();

      $classHistory = ClassHistoriesModel::create($validated);

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
      $classHistory = ClassHistoriesModel::with(['student', 'class', 'studyYear'])->findOrFail($id);

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
  public function update(ClassHistoriesRequest $request, $id)
  {
    try {
      $validated = $request->validated();
      $validated['updated_by'] = auth()->id();

      $classHistory = ClassHistoriesModel::findOrFail($id);
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
      $classHistory = ClassHistoriesModel::findOrFail($id);
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
      $classHistory = ClassHistoriesModel::withTrashed()->findOrFail($id);
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
