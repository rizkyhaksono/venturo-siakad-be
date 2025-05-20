<?php

namespace App\Http\Controllers\Api\v1\Admin;

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
      $spp = SPPModel::with([
        'studyYear'
      ])->paginate(10);

      return response()->json($spp);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Error fetching SPP data',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(SPPRequest $request)
  {
    $validatedData = $request->validated();

    try {
      $spp = SPPModel::create($validatedData);

      return response()->json($spp, 201);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Error creating SPP',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $spp = SPPModel::with([
      'studyYear'
    ])->find($id);

    if (!$spp) {
      return response()->json([
        'status' => false,
        'message' => 'SPP not found',
      ], 404);
    }

    return response()->json($spp);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(SPPRequest $request, $id)
  {
    $validatedData = $request->validated();

    $spp = SPPModel::find($id);

    if (!$spp) {
      return response()->json([
        'status' => false,
        'message' => 'SPP not found',
      ], 404);
    }

    try {
      $spp->update($validatedData);

      return response()->json($spp);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Error updating SPP',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $spp = SPPModel::find($id);

    if (!$spp) {
      return response()->json([
        'status' => false,
        'message' => 'SPP not found',
      ], 404);
    }

    try {
      $spp->delete();

      return response()->json([
        'status' => true,
        'message' => 'SPP deleted successfully'
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Error deleting SPP',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
