<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentsRequest;
use App\Models\StudentsModel;

class StudentsController extends Controller
{
  /**
   * Display a listing of the students.
   */
  public function index()
  {
    $students = StudentsModel::with(['class', 'studyYear'])->get();

    return response()->success([
      'status' => true,
      'data' => $students,
    ]);
  }

  /**
   * Store a newly created student.
   */
  public function store(StudentsRequest $request)
  {
    $validated = $request->validated();
    $validated['created_by'] = auth()->id();

    $student = StudentsModel::create($validated);

    return response()->success([
      'status' => true,
      'message' => 'Student created successfully',
      'data' => $student->load(['class', 'studyYear']),
    ]);
  }
}
