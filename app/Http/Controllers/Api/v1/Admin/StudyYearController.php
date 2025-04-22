<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyYearRequest;
use App\Http\Resources\Admin\StudyYearResource;
use App\Models\StudyYearModel;
use Exception;

class StudyYearController extends Controller
{
  /**
   * Display a listing of the study years.
   */
  public function index()
  {
    try {
      $studyYears = StudyYearModel::latest()->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => StudyYearResource::collection($studyYears),
        'meta' => [
          'current_page' => $studyYears->currentPage(),
          'last_page' => $studyYears->lastPage(),
          'per_page' => $studyYears->perPage(),
          'total' => $studyYears->total(),
        ],
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve study years',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created study year in storage.
   */
  public function store(StudyYearRequest $request)
  {
    try {
      $studyYear = StudyYearModel::create($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Study year created successfully',
        'data' => $studyYear,
      ], 201);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified study year.
   */
  public function show(StudyYearModel $studyYear)
  {
    try {
      return response()->json([
        'status' => 'success',
        'data' => new StudyYearResource($studyYear),
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified study year in storage.
   */
  public function update(StudyYearRequest $request, StudyYearModel $studyYear)
  {
    try {
      $studyYear->update($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Study year updated successfully',
        'data' => $studyYear,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified study year from storage.
   */
  public function destroy(StudyYearModel $studyYear)
  {
    try {
      $studyYear->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Study year deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore the specified study year.
   */
  public function restore($id)
  {
    try {
      $studyYear = StudyYearModel::withTrashed()->findOrFail($id);
      $studyYear->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Study year restored successfully',
        'data' => $studyYear,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore study year',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
