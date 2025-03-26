<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\RegistrationsModel;
use App\Models\StudentsModel;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
  /**
   * Register a new user (creates user, student, and pending registration)
   *
   * @param AuthRequest $request
   * @return JsonResponse
   */
  public function register(AuthRequest $request): JsonResponse
  {
    try {
      $validated = $request->validated();

      // Create the user
      $user = UsersModel::create([
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'phone_number' => $validated['phone_number'] ?? null,
      ]);

      // Handle photo upload if present
      if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('user-photos', 'public');
        $user->photo = $photoPath;
        $user->save();
      }

      // Create the student record linked to the user
      $student = StudentsModel::create([
        'user_id' => $user->id,
        'name' => $validated['username'], // Adjust this based on your needs
        'student_number' => $this->generateStudentNumber(), // Implement this method
        'created_by' => $user->id,
      ]);

      // Create registration record with pending status
      RegistrationsModel::create([
        'student_id' => $student->id, // Use student ID, not user ID
        'status' => 'pending',
        'created_by' => $user->id,
      ]);

      // Generate a Sanctum token for the user
      $token = $user->createToken('auth_token')->plainTextToken;

      return response()->json([
        'message' => 'Registration submitted successfully. Please wait for admin approval.',
        'user' => $user,
        'student' => $student,
        'access_token' => $token,
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Registration failed',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Login user and return token (checks registration status)
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function login(Request $request): JsonResponse
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    $user = UsersModel::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return response()->json([
        'message' => 'Invalid login credentials'
      ], 401);
    }

    // check if registration is pending or rejected and student status
    $student = StudentsModel::where('user_id', $user->id)->first();
    $registration = RegistrationsModel::where('student_id', $student->id)->first();

    if (!$registration || $registration->status !== 'accepted') {
      return response()->json([
        'message' => 'Your registration is pending approval or has been rejected'
      ], 403);
    }

    // Generate token regardless of registration status
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'message' => 'Login successful',
      'access_token' => $token,
      'user' => $user,
      'student' => $student
    ]);
  }

  /**
   * Approve or reject registration (Admin only)
   *
   * @param Request $request
   * @param string $registrationId
   * @return JsonResponse
   */
  public function updateRegistrationStatus(Request $request, string $registrationId): JsonResponse
  {
    // Add middleware to ensure only admins can access this
    $this->middleware('role:admin');

    $request->validate([
      'status' => 'required|in:accepted,rejected'
    ]);

    $registration = RegistrationsModel::findOrFail($registrationId);

    $registration->update([
      'status' => $request->status,
      'updated_by' => Auth::id()
    ]);

    return response()->json([
      'message' => "Registration {$request->status} successfully",
      'registration' => $registration
    ]);
  }

  /**
   * Logout user
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function logout(Request $request): JsonResponse
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'message' => 'Successfully logged out'
    ]);
  }

  /**
   * Get authenticated user
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function me(Request $request): JsonResponse
  {
    return response()->json([
      'message' => 'User retrieved successfully',
      'user' => $request->user(),
    ]);
  }

  /**
   * Generate a unique student number
   *
   * @return string
   */
  private function generateStudentNumber(): string
  {
    // Simple example: you can customize this logic
    return 'STU' . time() . rand(1000, 9999);
  }
}
