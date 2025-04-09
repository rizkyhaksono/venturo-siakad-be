<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RegistrationController extends Controller
{
  /**
   * Approve or reject registration (Admin only)
   *
   * @param Request $request
   * @param string $registrationId
   */
  public function updateRegistrationStatus(Request $request, string $registrationId)
  {
    try {
      $request->validate([
        'status' => 'required|in:accepted,rejected'
      ]);

      if (!Uuid::isValid($registrationId)) {
        return response()->failed([
          'status' => 'error',
          'message' => 'Invalid registration ID format'
        ], 400);
      }

      $registration = RegistrationModel::findOrFail($registrationId);
      $student = StudentModel::findOrFail($registration->student_id);

      if (!$registration->status || !$student->status) {
        return response()->failed([
          'status' => 'error',
          'message' => 'Registration or student status is already updated'
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

      return response()->success([
        'status' => 'success',
        'message' => 'Registration status updated successfully',
        'data' => $registration->load(['student', 'class', 'studyYear'])
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Registration or student not found'
      ], 404);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update registration status',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
