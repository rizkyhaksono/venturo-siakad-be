<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAssesmentRequest;
use App\Models\StudentAssesmentModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;

class StudentAssesmentController extends Controller
{
  /**
   * Display a listing of the resource, based on authenticated user student.
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page', 10);

    $student = StudentModel::where('user_id', auth()->user()->id)->first();
    $studentAssesments = StudentAssesmentModel::where('student_id', $student->id)
      ->paginate($perPage);

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
