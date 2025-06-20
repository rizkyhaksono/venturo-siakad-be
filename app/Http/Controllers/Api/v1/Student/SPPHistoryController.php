<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\SPPHistoryRequest;
use App\Models\SPPHistoryModel;
use App\Models\SPPModel;
use Exception;

class SPPHistoryController extends Controller
{
  /**
   * Display a listing of the resource based on the authenticated user.
   */
  public function index()
  {
    try {
      $userId = auth()->user()->id;

      $sppHistory = SPPHistoryModel::with([
        'user',
        'spp'
      ])->where('user_id', $userId)->paginate(10);

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
      $spp = SPPModel::findOrFail($validatedData['spp_id']);
      $validatedData['amount_paid'] = $spp->total;
      $validatedData['payment_status'] = 'pending';
      $validatedData['proof_payment'] = $request->file('proof_payment') ? $request->file('proof_payment')->store('proof_payments', 'public') : null;
      $validatedData['user_id'] = auth()->user()->id;
      $validatedData['created_by'] = auth()->user()->id;

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
