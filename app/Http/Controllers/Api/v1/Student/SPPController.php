<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPPRequest;
use App\Models\SPPModel;
use Exception;

class SPPController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $spp = SPPModel::with(['studyYear'])->paginate(10);

      return response()->json($spp);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Error fetching SPP data',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
