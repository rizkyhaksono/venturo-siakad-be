<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPPHistoryRequest;
use App\Models\SPPHistoryModel;
use App\Models\SPPModel;
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
      // Get SPP details to set the amount_paid
      $spp = SPPModel::findOrFail($validatedData['spp_id']);
      $validatedData['amount_paid'] = $spp->total;
      $validatedData['proof_payment'] = $request->file('proof_payment') ? $request->file('proof_payment')->store('proof_payments', 'public') : null;

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
      if (isset($validatedData['spp_id'])) {
        $spp = SPPModel::findOrFail($validatedData['spp_id']);
        $validatedData['amount_paid'] = $spp->total;
      }

      if (isset($validatedData['proof_payment'])) {
        $sppHistory = SPPHistoryModel::findOrFail($id);
        if ($sppHistory->proof_payment) {
          \Storage::disk('public')->delete($sppHistory->proof_payment);
        }
        $validatedData['proof_payment'] = $request->file('proof_payment')->store('proof_payments', 'public');
      }

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

  /**
   * Display the image of the proof of payment.
   */
  public function showProofPayment($id)
  {
    try {
      $sppHistory = SPPHistoryModel::findOrFail($id);

      if (!$sppHistory->proof_payment) {
        return response()->json([
          'status' => false,
          'message' => 'Proof of payment not found'
        ], 404);
      }

      $filePath = storage_path('app/public/' . $sppHistory->proof_payment);

      if (!file_exists($filePath)) {
        return response()->json([
          'status' => false,
          'message' => 'File not found'
        ], 404);
      }

      return response()->file($filePath);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Failed to retrieve proof of payment',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
