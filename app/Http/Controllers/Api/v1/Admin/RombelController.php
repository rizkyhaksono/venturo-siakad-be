<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RombelRequest;
use App\Http\Resources\Admin\RombelResource;
use App\Models\RombelModel;
use App\Models\ClassModel;

class RombelController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $rombels = RombelModel::with(
      'class',
      'studyYear',
      'teacher',
      'subjesubjectSchedulect',
      'student'
    )->get();

    return response()->json([
      'status' => 'success',
      'message' => 'Rombel list retrieved successfully',
      // 'data' => RombelResource::collection($rombels)
      'data' => $rombels
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(RombelRequest $request)
  {
    $validated = $request->validated();

    $rombel = RombelModel::create($validated);

    $class = ClassModel::find($rombel->class_id);
    $class->increment('total_rombel');
    $class->save();

    return response()->json([
      'status' => 'success',
      'message' => 'Rombel created successfully',
      // 'data' => new RombelResource($rombel->load('class', 'studyYear', 'teacher', 'subjectSchedules'))
      'data' => $rombel
    ], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(RombelModel $rombel)
  {
    return response()->json([
      'status' => 'success',
      'message' => 'Rombel retrieved successfully',
      // 'data' => new RombelResource($rombel->load('class', 'studyYear', 'teacher', 'subjectSchedules'))
      'data' => $rombel
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(RombelRequest $request, RombelModel $rombel)
  {
    $rombel->update($request->validated());

    return response()->success([
      'status' => 'success',
      'message' => 'Rombel updated successfully',
      // 'data' => new RombelResource($rombel->load('class', 'studyYear', 'teacher', 'subject'))
      'data' => $rombel
    ]);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(RombelModel $rombel)
  {
    $rombel->delete();

    $class = ClassModel::find($rombel->class_id);
    $class->decrement('total_rombel');
    $class->save();

    return response()->success([
      'status' => 'success',
      'message' => 'Rombel deleted successfully'
    ]);
  }
}
