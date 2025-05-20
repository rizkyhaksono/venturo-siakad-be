<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPPHistoryRequest;
use App\Models\SPPHistoryModel;
use Exception;

class SPPHistoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $sppHistory = SPPHistoryModel::with([
        'user',
        'spp'
      ])->paginate(10);

      return response()->json($sppHistory);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to retrieve SPP History',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(SPPHistoryRequest $request)
  {
    $validatedData = $request->validated();

    try {
      $sppHistory = SPPHistoryModel::create($validatedData);

      return response()->json($sppHistory, 201);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to create SPP History',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $sppHistory = SPPHistoryModel::with([
        'user',
        'spp'
      ])->findOrFail($id);

      return response()->json([
        'status' => true,
        'message' => 'SPP History details',
        'data' => $sppHistory
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to retrieve SPP History',
        'error' => $e->getMessage()
      ], 500);
    }
  }
  /**
   * Update the specified resource in storage.
   */
  public function update(SPPHistoryRequest $request, $id)
  {
    $validatedData = $request->validated();

    try {
      $sppHistory = SPPHistoryModel::findOrFail($id);
      $sppHistory->update($validatedData);

      return response()->json($sppHistory);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to update SPP History',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    try {
      $sppHistory = SPPHistoryModel::findOrFail($id);
      $sppHistory->delete();

      return response()->json([
        'status' => true,
        'message' => 'SPP History deleted successfully'
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to delete SPP History',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
