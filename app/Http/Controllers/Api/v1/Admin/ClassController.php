<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassRequest;
use App\Models\ClassModel;
use Exception;

class ClassController extends Controller
{
  public function index()
  {
    $classes = ClassModel::with(['studyYear'])
      ->latest()
      ->paginate(10);
    return response()->json($classes, 200);
  }

  public function store(ClassRequest $request)
  {
    try {
      $class = ClassModel::create($request->validated());
      return response()->json($class, 201);
    } catch (Exception $e) {
      return response()->json(['error' => 'Failed to create class'], 500);
    }
  }

  public function show(ClassModel $class)
  {
    return response()->json($class, 200);
  }

  public function update(ClassRequest $request, ClassModel $class)
  {
    try {
      $class->update($request->validated());
      return response()->json($class, 200);
    } catch (Exception $e) {
      return response()->json(['error' => 'Failed to update class'], 500);
    }
  }

  public function destroy(ClassModel $class)
  {
    try {
      $class->delete();
      return response()->json(null, 204);
    } catch (Exception $e) {
      return response()->json(['error' => 'Failed to delete class'], 500);
    }
  }
}
