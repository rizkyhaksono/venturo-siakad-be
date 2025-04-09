<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\TeacherModel;
use Exception;


class TeacherController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $teachers = TeacherModel::with(['user'])->latest()->paginate(10);

      return response()->success([
        'status' => 'success',
        'data' => $teachers,
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve teachers',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(TeacherRequest $request)
  {
    try {
      $validated = $request->validated();
      $teacher = TeacherModel::create($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'Teacher created successfully',
        'data' => $teacher->load('user'),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(TeacherModel $teacher)
  {
    try {
      return response()->success([
        'status' => 'success',
        'data' => $teacher->load('user'),
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve teacher',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
