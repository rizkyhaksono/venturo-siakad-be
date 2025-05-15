<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\TeacherModel;
use App\Http\Resources\Admin\TeacherResource;
use Exception;


class TeacherController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $teachers = TeacherModel::with(['homeroomTeachers.class', 'rombels'])->latest()->paginate(10);

      return response()->json([
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
  public function show(string $id)
  {
    try {
      $teacher = TeacherModel::with(['user'])->findOrFail($id);

      return response()->json([
        'status' => 'success',
        'data' => new TeacherResource($teacher),
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
