<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KKMRequest;
use App\Models\KKMModel;

class StudentAssesmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $studentAssesments = KKMModel::with([
      'student',
      'subject',
      'studyYear'
    ])->paginate(10);

    return response()->json([
      'status' => true,
      'message' => 'List of Student Assesments',
      'data' => $studentAssesments
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(KKMRequest $request)
  {
    $validatedData = $request->validated();

    $studentAssesment = KKMModel::create($validatedData);

    return response()->json([
      'status' => true,
      'message' => 'Student Assesment created successfully',
      'data' => $studentAssesment
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $studentAssesment = KKMModel::with([
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

    return response()->json([
      'status' => true,
      'message' => 'Student Assesment details',
      'data' => $studentAssesment
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(KKMRequest $request, $id)
  {
    $validatedData = $request->validated();

    $studentAssesment = KKMModel::find($id);

    if (!$studentAssesment) {
      return response()->json([
        'status' => false,
        'message' => 'Student Assesment not found',
      ], 404);
    }

    $studentAssesment->update($validatedData);

    return response()->json([
      'status' => true,
      'message' => 'Student Assesment updated successfully',
      'data' => $studentAssesment
    ]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $studentAssesment = KKMModel::find($id);

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
