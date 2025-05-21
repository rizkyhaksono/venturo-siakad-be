<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentAssesmentRequest;
use App\Models\StudentAssesmentModel;

class StudentAssesmentController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $studentAssesments = StudentAssesmentModel::with([
      'student',
      'subject',
      'studyYear'
    ])->paginate(10);

    return response()->json($studentAssesments);
  }
}
