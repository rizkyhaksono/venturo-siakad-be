<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAssesmentRequest;
use App\Models\StudentAssesmentModel;
use App\Models\RombelModel;

class StudentAssesmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $studentAssesments = StudentAssesmentModel::with([
      'student',
      'subjectSchedule',
      'studyYear',
      'teacher'
    ])->paginate(10);

    return response()->json($studentAssesments);
  }

  /**
   * Display rombels that listing students resource
   */
  public function studentAssessmentRombels()
  {
    $rombels = RombelModel::with(['students'])->get();

    if ($rombels->isEmpty()) {
      return response()->json([
        'status' => false,
        'message' => 'No rombels found',
      ], 404);
    }

    return response()->json($rombels);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StudentAssesmentRequest $request)
  {
    $validatedData = $request->validated();
    $studentAssesment = StudentAssesmentModel::create($validatedData);
    return response()->json($studentAssesment, 201);
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
    ])->find($id);

    if (!$studentAssesment) {
      return response()->json([
        'status' => false,
        'message' => 'Student Assesment not found',
      ], 404);
    }

    return response()->json($studentAssesment);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(StudentAssesmentRequest $request, $id)
  {
    $validatedData = $request->validated();
    $studentAssesment = StudentAssesmentModel::find($id);

    if (!$studentAssesment) {
      return response()->json([
        'status' => false,
        'message' => 'Student Assesment not found',
      ], 404);
    }

    $studentAssesment->update($validatedData);

    return response()->json($studentAssesment);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $studentAssesment = StudentAssesmentModel::find($id);

    if (!$studentAssesment) {
      return response()->json([
        'status' => false,
        'message' => 'Student Assesment not found',
      ], 404);
    }

    $studentAssesment->delete();

    return response()->json([
      'status' => true,
      'message' => 'Student Assesment deleted successfully',
    ]);
  }
}
