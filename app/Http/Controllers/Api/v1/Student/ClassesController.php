<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassesRequest;
use App\Models\ClassesModel;
use Illuminate\Http\JsonResponse;

class ClassesController extends Controller
{
  /**
   * Display a listing of classes.
   */
  public function index(): JsonResponse
  {
    $classes = ClassesModel::with(['studyYear'])
      ->latest()
      ->paginate(10);

    return response()->json([
      'status' => 'success',
      'data' => $classes,
    ]);
  }

  /**
   * Display the specified class.
   */
  public function show(string $id): JsonResponse
  {
    $class = ClassesModel::with(['studyYear'])->find($id);

    if (! $class) {
      return response()->json([
        'status' => 'error',
        'message' => 'Class not found',
      ], 404);
    }

    return response()->json([
      'status' => 'success',
      'data' => $class,
    ]);
  }
}
