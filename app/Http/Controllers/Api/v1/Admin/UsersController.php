<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Requests\UserRequest;
use App\Models\UsersModel;
use App\Http\Controllers\Controller;
use Exception;

class UsersController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index()
  {
    try {
      $users = UsersModel::with(['student', 'teacher'])->get();
      return response()->success([
        'status' => 'success',
        'data' => $users,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve users',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   *
   */
  public function show(string $id)
  {
    try {
      $user = UsersModel::with(['student', 'teacher'])->findOrFail($id);
      return response()->success([
        'status' => 'success',
        'data' => $user,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'User not found',
        'error' => $e->getMessage(),
      ], 404);
    }
  }

  /**
   * Get authenticated user details
   *
   * @param UserRequest $request
   */
  public function me(UserRequest $request)
  {
    try {
      $user = UsersModel::with(['student', 'teacher'])->findOrFail($request->user()->id);
      return response()->success([
        'user' => $user,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve user details',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update authenticated user details
   *
   * @param UserRequest $request
   */
  public function update(UserRequest $request)
  {
    try {
      $user = UsersModel::findOrFail($request->user()->id);
      $user->update($request->validated());

      if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('user-photos', 'public');
        $user->photo = $photoPath;
        $user->save();
      }

      return response()->success([
        'message' => 'User details updated successfully',
        'user' => $user,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update user details',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Delete authenticated user account
   *
   * @param UserRequest $request
   */
  public function destroy(UserRequest $request)
  {
    try {
      $user = UsersModel::findOrFail($request->user()->id);
      $user->delete();

      return response()->success([
        'message' => 'User account deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete user account',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
