<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleRequest;
use App\Models\UserRoleModel;
use Exception;

class UserRoleController extends Controller
{
  /**
   * Display a listing of user roles.
   */
  public function index()
  {
    try {
      $userRoles = UserRoleModel::with(['users'])
        ->latest()
        ->paginate(10);

      return response()->success([
        'status' => 'success',
        'data' => $userRoles,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve user roles',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created user role.
   */
  public function store(UserRoleRequest $request)
  {
    try {
      $validated = $request->validated();
      $userRole = UserRoleModel::create($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'User role created successfully',
        'data' => $userRole->load('roles'),
      ], 201);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create user role',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified user role.
   */
  public function show(string $id)
  {
    try {
      $userRole = UserRoleModel::with(['users'])->findOrFail($id);

      return response()->success([
        'status' => 'success',
        'data' => $userRole,
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve user role',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Update the specified user role.
   */
  public function update(UserRoleRequest $request, string $id)
  {
    try {
      $validated = $request->validated();
      $userRole = UserRoleModel::findOrFail($id);
      $userRole->update($validated);

      return response()->success([
        'status' => 'success',
        'message' => 'User role updated successfully',
        'data' => $userRole->load('roles'),
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to update user role',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Remove the specified user role.
   */
  public function destroy(string $id)
  {
    try {
      $userRole = UserRoleModel::findOrFail($id);
      $userRole->delete();

      return response()->success([
        'status' => 'success',
        'message' => 'User role deleted successfully',
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to delete user role',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Restore a soft-deleted user role.
   */
  public function restore(string $id)
  {
    try {
      $userRole = UserRoleModel::withTrashed()->findOrFail($id);
      $userRole->restore();

      return response()->success([
        'status' => 'success',
        'message' => 'User role restored successfully',
        'data' => $userRole->load('roles'),
      ], 200);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to restore user role',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
