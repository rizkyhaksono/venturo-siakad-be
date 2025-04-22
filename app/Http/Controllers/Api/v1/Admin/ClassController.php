<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassRequest;
use App\Models\ClassModel;
use App\Http\Resources\Admin\ClassResource;
use Exception;

class ClassController extends Controller
{
  /**
   * Display a listing of the resources.
   */
  public function index()
  {
    $classes = ClassModel::with(['studyYear'])
      ->latest()
      ->paginate(10);
    return ClassResource::collection($classes);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\ClassRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ClassRequest $request)
  {
    try {
      $class = ClassModel::create($request->validated());
      return response()->json($class, 201);
    } catch (Exception $e) {
      return response()->json(['error' => 'Failed to create class'], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\ClassModel  $class
   * @return \Illuminate\Http\Response
   */
  public function show(ClassModel $class)
  {
    try {
      $class->load(['studyYear']);
      return new ClassResource($class);
    } catch (Exception $e) {
      return response()->json(['error' => 'Class not found'], 404);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\ClassRequest  $request
   * @param  \App\Models\ClassModel  $class
   * @return \Illuminate\Http\Response
   */
  public function update(ClassRequest $request, ClassModel $class)
  {
    try {
      $class->update($request->validated());
      return response()->json($class, 200);
    } catch (Exception $e) {
      return response()->json(['error' => 'Failed to update class'], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\ClassModel  $class
   * @return \Illuminate\Http\Response
   */
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
