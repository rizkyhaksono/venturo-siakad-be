<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\StudentModel;

class StudentController extends Controller
{
  /**
   * Display a listing of the students.
   */
  public function index()
  {
    $students = StudentModel::with(['class', 'studyYear'])->get();

    return response()->success([
      'status' => true,
      'data' => $students,
    ]);
  }

  /**
   * Store a newly created student.
   */
  public function store(StudentRequest $request)
  {
    $validated = $request->validated();
    $validated['created_by'] = auth()->id();

    $student = StudentModel::create($validated);

    return response()->success([
      'status' => true,
      'message' => 'Student created successfully',
      'data' => $student->load(['class', 'studyYear']),
    ]);
  }
}
