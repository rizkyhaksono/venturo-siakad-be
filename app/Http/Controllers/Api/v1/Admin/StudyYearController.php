<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyYearRequest;
use App\Models\StudyYearsModel;
use Illuminate\Http\JsonResponse;
use Exception;

class StudyYearController extends Controller
{
  /**
   * Display a listing of the study years.
   */
  public function index(): JsonResponse
  {
    try {
      $studyYears = StudyYearsModel::latest()->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => $studyYears,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to retrieve study years',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created study year in storage.
   */
  public function store(StudyYearRequest $request): JsonResponse
  {
    try {
      $studyYear = StudyYearsModel::create($request->validated());

      return response()->json([
        'status' => 'success',
        'data' => $studyYear,
      ], 201);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to create study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified study year.
   */
  public function show(StudyYearsModel $studyYear): JsonResponse
  {
    try {
      return response()->json([
        'status' => 'success',
        'data' => $studyYear,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to retrieve study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified study year in storage.
   */
  public function update(StudyYearRequest $request, StudyYearsModel $studyYear): JsonResponse
  {
    try {
      $studyYear->update($request->validated());

      return response()->json([
        'status' => 'success',
        'data' => $studyYear,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to update study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified study year from storage.
   */
  public function destroy(StudyYearsModel $studyYear): JsonResponse
  {
    try {
      $studyYear->delete();

      return response()->json([
        'status' => 'success',
        'message' => 'Study year deleted successfully',
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to delete study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore the specified study year.
   */
  public function restore($id): JsonResponse
  {
    try {
      $studyYear = StudyYearsModel::withTrashed()->findOrFail($id);
      $studyYear->restore();

      return response()->json([
        'status' => 'success',
        'message' => 'Study year restored successfully',
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to restwore study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
