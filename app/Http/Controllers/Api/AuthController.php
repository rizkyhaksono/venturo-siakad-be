<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User\AuthHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Helpers\User\UserHelper;
use App\Models\RegistrationModel;
use App\Models\UserRoleModel;

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

        $user = auth()->user();
        $role = UserRoleModel::where('id', $user->m_user_roles_id)->first();
        $registration = RegistrationModel::where('user_id', $user->id)->first();

        if ($registration->status == 'rejected') {
            return response()->failed('Akun anda ditolak, silahkan hubungi admin', 422);
        }

        if ($registration->status == 'pending') {
            return response()->success(
                array_merge($login['data'], ['role' => $role->name]),
                'Akun anda sedang dalam proses verifikasi, silahkan tunggu'
            );
        }

        if ($registration->status == 'accepted') {
            return response()->success(
                array_merge($login['data'], ['role' => $role->name]),
                'Akun anda sudah diverifikasi, silahkan login'
            );
        }

        return response()->success(
            array_merge($login['data'], ['role' => $role->name])
        );
    }

    /**
     * Register a new user
     *
     * @bodyParam email string required The email of the user. Example: user@example.com
     * @bodyParam name string required The full name of the user. Example: John Doe
     * @bodyParam password string required The password. Minimum 6 characters. Example: secret123
     * @bodyParam phone_number string required The user's phone number. Example: 08123456789
     * @bodyParam photo file The user's profile picture (jpg, png, etc.)
     *
     * This is for student registration only.
     */
    public function registerStudent(UserRequest $request)
    {
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
        $payload['m_user_roles_id'] = "c97697f8-6d31-492d-9014-efb96b668c89";

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

    /**
     * Send password reset link
     * 
     * @bodyParam email string required The email address of the user. Example: user@example.com
     */
    public function forgotPassword(Request $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $response = AuthHelper::forgotPassword($request->email);

        if (!$response['status']) {
            return response()->failed($response['error'], 422);
        }

        return response()->success([], 'Password reset link has been sent to your email');
    }

    /**
     * Reset password
     * 
     * @bodyParam token string required The reset token received in email. Example: 1234abcd
     * @bodyParam email string required The email address of the user. Example: user@example.com
     * @bodyParam password string required The new password. Example: newpassword123
     * @bodyParam password_confirmation string required Password confirmation. Example: newpassword123
     */
    public function resetPassword(Request $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $response = AuthHelper::resetPassword(
            $request->token,
            $request->email,
            $request->password
        );

        if (!$response['status']) {
            return response()->failed($response['error'], 422);
        }

        return response()->success([], 'Password has been reset successfully');
    }
}
