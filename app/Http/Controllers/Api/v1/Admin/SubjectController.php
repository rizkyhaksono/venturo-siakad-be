<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\SubjectModel;
use Exception;

class SubjectController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $subjects = SubjectModel::all();

      return response()->success([
        'status' => 'success',
        'data' => $subjects,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subjects',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   */
  public function store(SubjectRequest $request)
  {
    try {
      $subject = SubjectModel::create($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Subject created successfully',
        'data' => $subject,
      ], 201);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create subject',
        'error' => $e->getMessage(),
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
      $subject = SubjectModel::findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $subject,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   */
  public function update(SubjectRequest $request, string $id)
  {
    try {
      $subject = SubjectModel::findOrFail($id);
      $subject->update($request->validated());

      return response()->success([
        'status' => 'success',
        'message' => 'Subject updated successfully',
        'data' => $subject,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update subject',
        'error' => $e->getMessage(),
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
      $subject = SubjectModel::findOrFail($id);
      $subject->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete subject',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore the specified resource from storage.
   *
   */
  public function restore(string $id)
  {
    try {
      $subject = SubjectModel::withTrashed()->findOrFail($id);
      $subject->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'Subject restored successfully',
        'data' => $subject,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore subject',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
