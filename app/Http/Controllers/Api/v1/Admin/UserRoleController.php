<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Helpers\User\RoleHelper;
use App\Http\Controllers\Controller;
// use App\Http\Requests\Role\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
  private $roleHelper;

  public function __construct()
  {
    $this->roleHelper = new RoleHelper;
  }

  /**
   * Delete data role
   *
   * @author Wahyu Agung <wahyuagung26@email.com>
   *
   * @param  mixed  $id
   */
  public function destroy($id)
  {
    $role = $this->roleHelper->delete($id);

    if (! $role) {
      return response()->failed(['Mohon maaf role tidak ditemukan']);
    }

    return response()->success($role, 'Role berhasil dihapus');
  }

  /**
   * Mengambil data role dilengkapi dengan pagination
   *
   * @author Wahyu Agung <wahyuagung26@email.com>
   */
  public function index(Request $request)
  {
    $filter = [
      'name' => $request->name ?? '',
    ];
    $roles = $this->roleHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

    return response()->success([
      'list' => is_array($roles['data'] ?? null) ? $roles['data'] : [],
    ]);
  }

  /**
   * Menampilkan role secara spesifik dari tabel user_role
   *
   * @author Wahyu Agung <wahyuagung26@email.com>
   *
   * @param  mixed  $id
   */
  public function show($id)
  {
    $role = $this->roleHelper->getById($id);

    if (! ($role['status'])) {
      return response()->failed(['Data role tidak ditemukan'], 404);
    }

    return response()->success(new RoleResource($role['data']));
  }

  /**
   * Membuat data role baru & disimpan ke tabel user_role
   *
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'access' => 'required|array',
    ]);

    $payload = $request->only(['name', 'access']);
    $payload['access'] = implode(', ', $payload['access']);

    $role = $this->roleHelper->create($payload);
    if (! $role['status']) {
      return response()->failed($role['error']);
    }

    return response()->success(new RoleResource($role['data']), 'Role berhasil ditambahkan');
  }

  /**
   * Mengubah data role di tabel user_role
   *
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'access' => 'required|array',
    ]);

    $payload = $request->only(['name', 'access']);

    $payload['access'] = is_array($payload['access'])
      ? implode(', ', $payload['access'])
      : $payload['access'];

    $role = $this->roleHelper->update($payload, $id);

    if (!$role['status']) {
      return response()->failed($role['error'] ?? 'Failed to update role');
    }

    return response()->success(new RoleResource($role['data']), 'Role berhasil diubah');
  }
}
