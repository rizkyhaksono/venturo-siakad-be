<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\RegistrationsModel;
use App\Models\StudentsModel;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  /**
   * Register a new user (creates user, student, and pending registration)
   *
   * @param AuthRequest $request
   */
  public function register(AuthRequest $request)
  {
    try {
      $validated = $request->validated();
      $user = UsersModel::create([
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'phone_number' => $validated['phone_number'] ?? null,
      ]);

      if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('user-photos', 'public');
        $user->photo = $photoPath;
        $user->save();
      }

      $student = StudentsModel::create([
        'user_id' => $user->id,
        'name' => $validated['username'],
        'student_number' => $this->generateStudentNumber(),
        'created_by' => $user->id,
      ]);

      RegistrationsModel::create([
        'student_id' => $student->id,
        'status' => 'pending',
        'created_by' => $user->id,
      ]);

      $token = $user->createToken('auth_token')->plainTextToken;

      return response()->success([
        'message' => 'Registration submitted successfully. Please wait for admin approval.',
        'user' => $user,
        'student' => $student,
        'access_token' => $token,
      ], 201);
    } catch (\Exception $e) {
      return response()->failed([
        'message' => 'Registration failed',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Login user and return token (checks registration status)
   *
   * @param Request $request
   */
  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    $user = UsersModel::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
      return response()->failed([
        'message' => 'Invalid login credentials'
      ], 401);
    }

    $student = StudentsModel::where('user_id', $user->id)->first();
    $registration = RegistrationsModel::where('student_id', $student->id)->first();

    if (!$registration || $registration->status !== 'accepted') {
      return response()->failed([
        'message' => 'Your registration is pending approval or has been rejected'
      ], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->success([
      'access_token' => $token,
      'message' => 'Login successful',
      'user' => $user,
      'student' => $student,
    ], 200);
  }

  /**
   * Logout user (revoke token)
   *
   * @param Request $request
   */
  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->success([
      'message' => 'Logout successful',
    ], 200);
  }

  /**
   * Get authenticated user details
   *
   * @param Request $request
   */
  public function me(Request $request)
  {
    return response()->success([
      'user' => $request->user(),
    ], 200);
  }

  /**
   * Generate a unique student number
   *
   * @return string
   */
  private function generateStudentNumber(): string
  {
    return 'STU' . time() . rand(1000, 9999);
  }
}
