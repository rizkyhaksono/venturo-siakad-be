<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectScheduleRequest;
use App\Models\SubjectScheduleModel;
use Exception;

class SubjectScheduleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $subjectSchedules = SubjectScheduleModel::with(['class', 'subject', 'teacher', 'subjectHour'])->get();

      return response()->success([
        'status' => 'success',
        'data' => $subjectSchedules,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject schedules',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   */
  public function store(SubjectScheduleRequest $request)
  {
    try {
      $subjectSchedules = SubjectScheduleModel::create($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Subject schedule created successfully',
        'data' => $subjectSchedules->load(['class', 'subject', 'teacher', 'subjectHour'])
      ], 201);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create subject schedule',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   */
  public function show(string $id)
  {
    try {
      $subjectSchedules = SubjectScheduleModel::with(['class', 'subject', 'teacher', 'subjectHour'])
        ->findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $subjectSchedules,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject schedule',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param SubjectScheduleRequest $request
   */
  public function update(SubjectScheduleRequest $request, string $id)
  {
    try {
      $subjectSchedules = SubjectScheduleModel::findOrFail($id);
      $subjectSchedules->update($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Subject schedule updated successfully',
        'data' => $subjectSchedules->load(['class', 'subject', 'teacher', 'subjectHour'])
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update subject schedule',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   */
  public function destroy(string $id)
  {
    try {
      $subjectSchedules = SubjectScheduleModel::findOrFail($id);
      $subjectSchedules->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject schedule deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete subject schedule',
        'error' => $e->getMessage()
      ], 500);
    }
  }


  /**
   * Restore the specified resource in storage.
   *
   */

  public function restore(string $id)
  {
    try {
      $subjectSchedules = SubjectScheduleModel::withTrashed()
        ->findOrFail($id);
      $subjectSchedules->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject schedule restored successfully',
        'data' => $subjectSchedules->load(['class', 'subject', 'teacher', 'subjectHour'])
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore subject schedule',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
