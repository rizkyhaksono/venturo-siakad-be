<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAssesmentRequest;
use App\Models\StudentAssesmentModel;
use App\Models\KKMModel;
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

  /**
   * Display the specified resource based on student ID.
   * Get all assessments for a specific student.
   * @param  int  $studentId
   * @return \Illuminate\Http\Response
   */
  public function show($studentId)
  {
    $studentAssessments = StudentAssesmentModel::with([
      'subjectSchedule.subject.kkm',
      'studyYear',
    ])->where('student_id', $studentId)->get();

    if ($studentAssessments->isEmpty()) {
      return response()->json([
        'message' => 'No assessments found for this student'
      ], 404);
    }

    return response()->json($studentAssessments);
  }

  /**
   * Update the specified resource in storage.
   * @param  \App\Http\Requests\StudentAssesmentRequest  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   * @throws \Illuminate\Validation\ValidationException
   */
  public function update(StudentAssesmentRequest $request, $id)
  {
    $assessment = StudentAssesmentModel::find($id);

    if (!$assessment) {
      return response()->json([
        'message' => 'Assessment not found'
      ], 404);
    }

    $validatedData = $request->validated();

    try {
      $assessment->update($validatedData);
      return response()->json([
        'message' => 'Assessment updated successfully',
        'data' => $assessment
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Failed to update assessment',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
