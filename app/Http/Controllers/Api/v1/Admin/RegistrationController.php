<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RegistrationModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class RegistrationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $registrations = RegistrationModel::with(['user', 'student', 'teacher'])
      ->where('status', '!=', null)
      ->orderBy('created_at', 'desc')
      ->get();
    if ($registrations->isEmpty()) {
      return response()->failed([
        'status' => 'error',
        'message' => 'No registrations found'
      ], 404);
    }

    return response()->success([
      'status' => 'success',
      'message' => 'Registration list retrieved successfully',
      'data' => $registrations
    ]);
  }

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
        'status' => 'required|in:accepted,rejected',
        'assigned_to' => 'required|in:student,teacher',
      ]);

      if (!Uuid::isValid($registrationId)) {
        return response()->failed([
          'status' => 'error',
          'message' => 'Invalid registration ID format'
        ], 400);
      }

      $registration = RegistrationModel::findOrFail($registrationId);

      // Check if the user exists in the respective role table
      if ($request->assigned_to === 'student') {
        $exists = StudentModel::where('user_id', $registration->user_id)->exists();
        if (!$exists) {
          $user = $registration->user;
          $studentNumber = 'STU-' . date('Y') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

          StudentModel::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $registration->user_id,
            'name' => $user->name,
            'student_number' => $studentNumber,
            'class' => 'Unassigned',
            'status' => $request->status,
            'created_by' => Auth::id()
          ]);
        }
      } elseif ($request->assigned_to === 'teacher') {
        $exists = TeacherModel::where('user_id', $registration->user_id)->exists();
        if (!$exists) {
          $user = $registration->user;
          $employeeNumber = 'TCH-' . date('Y') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

          TeacherModel::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $registration->user_id,
            'name' => $user->name,
            'employee_number' => $employeeNumber,
            'subject' => 'Unassigned',
            'status' => $request->status,
            'created_by' => Auth::id()
          ]);
        }
      }

      if (!$registration->status) {
        return response()->failed([
          'status' => 'error',
          'message' => 'Registration or student status is already updated'
        ], 400);
      }

      $registration->update([
        'status' => $request->status,
        'updated_by' => Auth::id()
      ]);

      return response()->success([
        'status' => 'success',
        'message' => 'Registration status updated successfully',
        'data' => $registration->load(['user', 'student', 'teacher'])
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
