<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Http\Resources\UserResource;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\ResetPasswordEmail;

/**
 * Helper khusus untuk authentifikasi pengguna
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class AuthHelper extends Venturo
{
    /**
     * Proses validasi email dan password
     * jika terdaftar pada database dilanjutkan generate token JWT
     *
     * @param  string  $email
     * @param  string  $password
     * @return void
     */
    public static function login($email, $password)
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if (! $token = JWTAuth::attempt($credentials)) {
                return [
                    'status' => false,
                    'error' => ['Kombinasi email dan password yang kamu masukkan salah'],
                ];
            }
        } catch (JWTException $e) {
            return [
                'status' => false,
                'error' => ['Could not create token.'],
            ];
        }

        return [
            'status' => true,
            'data' => self::createNewToken($token),
        ];
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected static function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->user()),
        ];
    }

    public static function logout()
    {
        try {
            $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

            if ($removeToken) {
                //return response JSON
                return [
                    'status' => true,
                    'message' => 'Logout Success!',
                ];
            }
        } catch (JWTException $e) {
            dd($e, JWTAuth::getToken());

            return [
                'status' => false,
                'error' => ['Could not logout token.'],
            ];
        }
    }

    /**
     * Send password reset email
     * 
     * @param string $email User's email address
     * @return array
     */
    public static function forgotPassword($email)
    {
        try {
            $user = UserModel::where('email', $email)->first();

            if (!$user) {
                return [
                    'status' => false,
                    'error' => 'User not found'
                ];
            }

            $token = Str::random(60);

            $cacheKey = 'pwd_reset_' . $email;
            cache()->put($cacheKey, [
                'token' => Hash::make($token),
                'expires_at' => Carbon::now()->addHours(1)
            ], Carbon::now()->addHours(1));

            $user->updated_security = Carbon::now();
            $user->save();

            try {
                Mail::to($user->email)->send(new ResetPasswordEmail($token, $email));
                \Log::info('Password reset email sent to ' . $user->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send password reset email: ' . $e->getMessage());
            }

            return [
                'status' => true,
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reset user's password
     *
     * @param string $token Reset token
     * @param string $email User's email
     * @param string $password New password
     * @return array
     */
    public static function resetPassword($token, $email, $password)
    {
        try {
            $user = UserModel::where('email', $email)->first();

            if (!$user) {
                return [
                    'status' => false,
                    'error' => 'User not found'
                ];
            }

            $cacheKey = 'pwd_reset_' . $email;
            $resetData = cache()->get($cacheKey);

            if (!$resetData) {
                return [
                    'status' => false,
                    'error' => 'Invalid or expired reset request'
                ];
            }

            if (!Hash::check($token, $resetData['token'])) {
                return [
                    'status' => false,
                    'error' => 'Invalid token'
                ];
            }

            if (Carbon::now()->isAfter($resetData['expires_at'])) {
                return [
                    'status' => false,
                    'error' => 'Token has expired'
                ];
            }

            $user->password = Hash::make($password);
            $user->updated_security = null;
            $user->save();

            cache()->forget($cacheKey);

            return [
                'status' => true,
                'data' => []
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
