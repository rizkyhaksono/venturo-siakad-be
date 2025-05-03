<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Http\Resources\UserResource;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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

    public static function forgotPassword($email)
    {
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return [
                    'status' => false,
                    'error' => 'User not found'
                ];
            }

            $token = Str::random(60);

            DB::table('password_resets')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            // Send email with reset link
            Mail::send('emails.reset-password', [
                'token' => $token,
                'email' => $email
            ], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Password Notification');
            });

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

    public static function resetPassword($token, $email, $password)
    {
        try {
            $reset = DB::table('password_resets')
                ->where('email', $email)
                ->first();

            if (!$reset || !Hash::check($token, $reset->token)) {
                return [
                    'status' => false,
                    'error' => 'Invalid token'
                ];
            }

            if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
                return [
                    'status' => false,
                    'error' => 'Token has expired'
                ];
            }

            $user = User::where('email', $email)->first();
            $user->password = Hash::make($password);
            $user->save();

            DB::table('password_resets')->where('email', $email)->delete();

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
