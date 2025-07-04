<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
  private $userHelper;

  public function __construct()
  {
    $this->userHelper = new UserHelper;
  }

  /**
   * Delete data user
   *
   * @param  mixed  $id
   */
  public function destroy($id)
  {
    $user = $this->userHelper->delete($id);

    if (! $user) {
      return response()->failed(['Mohon maaf data pengguna tidak ditemukan']);
    }

    return response()->success($user, 'User berhasil dihapus');
  }

  /**
   * Mengambil data user dilengkapi dengan pagination
   *
   */
  public function index(Request $request)
  {
    $filter = [
      'name' => $request->name ?? '',
      'email' => $request->email ?? '',
    ];
    $users = $this->userHelper->getAll($filter, $request->page ?? 1, $request->per_page ?? 25, $request->sort ?? '');

    return response()->success([
      'list' => is_array($users['data'] ?? null) ? $users['data'] : [],
    ]);
  }

  /**
   * Menampilkan user secara spesifik dari tabel m_user
   *
   * @param  mixed  $id
   */
  public function show($id)
  {
    $user = $this->userHelper->getById($id);

    if (! ($user['status'])) {
      return response()->failed(['Data user tidak ditemukan'], 404);
    }

    return response()->success(new UserResource($user['data']));
  }

  /**
   * Membuat data user baru & disimpan ke tabel m_user
   *
   */
  public function store(UserRequest $request)
  {
    /**
     * Menampilkan pesan error ketika validasi gagal
     * pengaturan validasi bisa dilihat pada class app/Http/request/User/CreateRequest
     */
    if (isset($request->validator) && $request->validator->fails()) {
      return response()->failed($request->validator->errors());
    }

    // $payload = $request->only(['email', 'name', 'password', 'photo', 'phone_number', 'm_user_roles_id']);
    $payload = $request->only([
      'name',
      'email',
      'wali',
      'pekerjaan',
      'birth_date',
      'address',
      'gender',
      'password',
      'photo',
      'phone_number'
    ]);
    $payload['m_user_roles_id'] = "a9c48018-128f-4fdc-b7a8-eef3d22ea5ea";

    $user = $this->userHelper->create($payload);

    if (! $user['status']) {
      return response()->failed($user['error']);
    }

    return response()->success(new UserResource($user['data']), 'User berhasil ditambahkan');
  }

  /**
   * Mengubah data user di tabel m_user
   *
   */
  public function update(UserRequest $request, $id)
  {
    /**
     * Menampilkan pesan error ketika validasi gagal
     * pengaturan validasi bisa dilihat pada class app/Http/request/User/UpdateRequest
     */
    if (isset($request->validator) && $request->validator->fails()) {
      return response()->failed($request->validator->errors());
    }

    $payload = $request->only(['email', 'name', 'password', 'photo', 'phone_number', 'm_user_roles_id']);
    $user = $this->userHelper->update($payload, $id ?? 0);

    if (! $user['status']) {
      return response()->failed($user['error']);
    }

    return response()->success(new UserResource($user['data']), 'User berhasil diubah');
  }
}
