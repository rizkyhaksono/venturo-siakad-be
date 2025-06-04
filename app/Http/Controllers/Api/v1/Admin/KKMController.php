<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KKMRequest;
use App\Models\KKMModel;
use Illuminate\Http\Request;
use Exception;

class KKMController extends Controller
{
  /**
   * Display a listing of the resources.
   */
  public function index(Request $request)
  {
    $perPage = $request->input('per_page', 10);

    $kkm = KKMModel::with(['subject'])
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
    return response()->json($kkm);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(KKMRequest $request)
  {
    try {
      $kkm = KKMModel::create($request->validated());
      return response()->json($kkm, 201);
    } catch (Exception $e) {
      return response()->json([
        'error' => 'Failed to create KKM',
        'message' => $e->getMessage(),
        'data' => $request->validated()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(KKMModel $kkm)
  {
    try {
      $kkm->load(['subject']);
      return response()->json($kkm);
    } catch (Exception $e) {
      return response()->json(['error' => 'KKM not found'], 404);
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(KKMRequest $request, KKMModel $kkm)
  {
    try {
      $kkm->update($request->validated());
      return response()->json($kkm);
    } catch (Exception $e) {
      return response()->json(['error' => 'Failed to update KKM'], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(KKMModel $kkm)
  {
    try {
      $kkm->delete();
      return response()->success(['message' => 'KKM deleted successfully']);
    } catch (Exception $e) {
      return response()->failed(['error' => 'Failed to delete KKM'], 500);
    }
  }

  /**
   * Display only trashed resources.
   */
  public function trashed()
  {
    $kkm = KKMModel::onlyTrashed()
      ->with(['subject'])
      ->orderBy('deleted_at', 'desc')
      ->paginate(10);
    return response()->success($kkm);
  }

  /**
   * Restore the specified resource from storage.
   */
  public function restore($id)
  {
    try {
      $kkm = KKMModel::withTrashed()->findOrFail($id);
      $kkm->restore();
      return response()->success(['message' => 'KKM restored successfully']);
    } catch (Exception $e) {
      return response()->failed(['error' => 'Failed to restore KKM'], 500);
    }
  }

  /**
   * Force delete the specified resource from storage.
   */
  public function forceDelete($id)
  {
    try {
      $kkm = KKMModel::withTrashed()->findOrFail($id);
      $kkm->forceDelete();
      return response()->success(['message' => 'KKM permanently deleted']);
    } catch (Exception $e) {
      return response()->failed(['error' => 'Failed to permanently delete KKM'], 500);
    }
  }
}
