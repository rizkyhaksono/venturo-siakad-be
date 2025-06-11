<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAssesmentRequest;
use App\Models\StudentAssesmentModel;
use App\Http\Resources\Teacher\StudentAssessment;
use Illuminate\Http\Request;

class StudentAssesmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page', 10);

    $studentAssesments = StudentAssesmentModel::with([
      'student',
      'subjectSchedule.subject',
      'studyYear'
    ])->paginate($perPage);

    return response()->json($studentAssesments);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StudentAssesmentRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StudentAssesmentRequest $request)
  {
    try {
      $assessments = $request->all();

      if (!isset($assessments[0])) {
        $assessments = [$assessments];
      }

      $createdAssessments = [];

      foreach ($assessments as $assessmentData) {
        $singleRequest = new StudentAssesmentRequest();
        $singleRequest->merge($assessmentData);
        $validator = validator($assessmentData, $singleRequest->rules(), $singleRequest->messages());

        if ($validator->fails()) {
          return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
          ], 422);
        }

        $assessment = StudentAssesmentModel::create($assessmentData);
        $createdAssessments[] = $assessment;
      }

      return response()->json([
        'message' => 'Student assessments created successfully',
        'data' => $createdAssessments
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to create student assessments',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
