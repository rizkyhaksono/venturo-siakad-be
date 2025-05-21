<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAssesmentRequest;
use App\Models\StudentAssesmentModel;

class StudentAssesmentController extends Controller
{
  /**
   * Display a listing of the resource, based on authenticated user student.
   */
  public function index()
  {
    $studentAssesments = StudentAssesmentModel::with([
      'student',
      'subject',
      'studyYear'
    ])->where('student_id', auth()->user()->id)->paginate(10);

    return response()->json($studentAssesments);
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $studentAssesment = StudentAssesmentModel::with([
      'student',
      'subject',
      'studyYear'
    ])->where('student_id', auth()->user()->id)->find($id);

    if (!$studentAssesment) {
      return response()->json([
        'status' => false,
        'message' => 'Student Assesment not found',
      ], 404);
    }

    return response()->json($studentAssesment);
  }
}
