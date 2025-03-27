<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationsModel;
use App\Models\StudentsModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RegistrationController extends Controller
{
  /**
   * Approve or reject registration (Admin only)
   *
   * @param Request $request
   * @param string $registrationId
   * @return JsonResponse
   */
  public function updateRegistrationStatus(Request $request, string $registrationId): JsonResponse
  {
    try {
      $request->validate([
        'status' => 'required|in:accepted,rejected'
      ]);

      if (!Uuid::isValid($registrationId)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Invalid registration ID format'
        ], 400);
      }

      $registration = RegistrationsModel::findOrFail($registrationId);
      $student = StudentsModel::findOrFail($registration->student_id);

      if (!$registration->status || !$student->status) {
        return response()->json([
          'status' => 'error',
          'message' => 'Registration has been processed'
        ], 400);
      }

      $registration->update([
        'status' => $request->status,
        'updated_by' => Auth::id()
      ]);

      $student->update([
        'status' => $request->status,
        'updated_by' => Auth::id()
      ]);

      return response()->json([
        'status' => 'success',
        'message' => "Registration {$request->status} successfully",
        'data' => $registration->load('student')
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Registration not found'
      ], 404);
    } catch (Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Failed to update registration status',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
