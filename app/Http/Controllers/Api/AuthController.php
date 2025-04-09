<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Helpers\User\UserHelper;
use App\Models\RegistrationModel;

class AuthController extends Controller
{
    private $userHelper;

    public function __construct()
    {
        $this->userHelper = new UserHelper;
    }

    /**
     * Login user
     *
     * @bodyParam email string required The email of the user. Example: user@example.com
     * @bodyParam password string required The password. Example: secret123
     */
    public function login(AuthRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/User/UpdateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        $login = AuthHelper::login($credentials['email'], $credentials['password']);

        if (! $login['status']) {
            return response()->failed($login['error'], 422);
        }

        return response()->success($login['data']);
    }

    /**
     * Register a new user
     *
     * @bodyParam email string required The email of the user. Example: user@example.com
     * @bodyParam name string required The full name of the user. Example: John Doe
     * @bodyParam password string required The password. Minimum 6 characters. Example: secret123
     * @bodyParam phone_number string required The user's phone number. Example: 08123456789
     * @bodyParam photo file The user's profile picture (jpg, png, etc.)
     */
    public function register(UserRequest $request)
    {
        $payload = $request->only(['email', 'name', 'password', 'photo', 'phone_number']);
        $payload['m_user_roles_id'] = "8e403aa5-2658-4726-a44f-3b8fadff6e3a";

        $user = $this->userHelper->create($payload);
        RegistrationModel::create([
            'user_id' => $user['data']['id'],
            'status' => 'pending',
            'created_by' => $user['data']['id'],
        ]);

        if (! $user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), 'User berhasil ditambahkan');
    }

    /**
     * Mengambil profile user yang sedang login
     *
     * @return void
     */
    public function profile()
    {
        return response()->success(new UserResource(auth()->user()));
    }

    /**
     * Mengambil profile user yang sedang login
     *
     * @return void
     */
    public function logout()
    {

        $logout = AuthHelper::logout();

        if (! $logout['status']) {
            return response()->failed($logout['error'], 422);
        }

        return response()->success([], 'Logout Success !');
    }
}
