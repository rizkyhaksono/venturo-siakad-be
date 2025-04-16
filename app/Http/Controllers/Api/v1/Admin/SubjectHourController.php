<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectHourRequest;
use App\Models\SubjectHourModel;
use Exception;

class SubjectHourController extends Controller
{
  /**
   * Display a listing of the resource
   */
  public function index()
  {
    try {
      $subjectHours = SubjectHourModel::all();

      return response()->success([
        'status' => 'success',
        'data' => $subjectHours,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject hours',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param SubjectHourRequest $request
   */
  public function store(SubjectHourRequest $request)
  {
    try {
      $subjectHours = SubjectHourModel::create($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Subject hours created successfully',
        'data' => $subjectHours,
      ], 201);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create subject hours',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param SubjectHourModel $subjectHours
   */
  public function show(SubjectHourModel $subjectHours)
  {
    try {
      return response()->success([
        'status' => 'success',
        'data' => $subjectHours,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject hours',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param int $id
   * @param SubjectHourRequest $request
   */
  public function update($id, SubjectHourRequest $request)
  {
    try {
      $subjectHours = SubjectHourModel::findOrFail($id);
      $subjectHours->update($request->validated());
      $subjectHours->updated_by = auth()->id();
      $subjectHours->save();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject hours updated successfully',
        'data' => $subjectHours->fresh(),
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update subject hours',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param SubjectHourModel $subjectHours
   */
  public function destroy($id)
  {
    try {
      $subjectHours = SubjectHourModel::find($id);

      if (!$subjectHours) {
        return response()->failed([
          'status' => 'error',
          'message' => 'Subject hours not found',
        ], 404);
      }

      if ($subjectHours->subjectSchedules && $subjectHours->subjectSchedules->count() > 0) {
        return response()->failed([
          'status' => 'error',
          'message' => 'Cannot delete subject hours that are in use',
        ], 400);
      }

      $subjectHours->deleted_by = auth()->id();
      $subjectHours->save();
      $subjectHours->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject hours deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete subject hours',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore the specified resource from storage.
   *
   * @param int $id
   */
  public function restore($id)
  {
    try {
      $subjectHours = SubjectHourModel::withTrashed()->findOrFail($id);
      $subjectHours->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject hours restored successfully',
        'data' => $subjectHours,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore subject hours',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
