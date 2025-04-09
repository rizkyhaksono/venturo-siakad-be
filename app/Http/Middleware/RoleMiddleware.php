<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Models\UserRoleModel;

class RoleMiddleware extends BaseMiddleware
{
    /**
     * Middleware untuk mengecek permission user ketika melakukan request ke salah satu routes
     * Pada saat membuat sebuah routes, tambahkan hak akses apa yang boleh mengakses endpoint ini
     *
     * Contohnya : Route::get('/users', [UserController::class, 'index'])->middleware(['auth.api','role:user_view']);
     *
     * Routes di atas hanya dapat diakses jika request dilengkapi dengan token JWT dan user memiliki akses "user_view"
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function handle($request, Closure $next, $roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userRole = UserRoleModel::where('id', $user->m_user_roles_id)->first();

            if (! $userRole) {
                return response()->failed(['User role tidak ditemukan'], 403);
            }

            $roleName = strtolower($userRole->name);
            $requiredRoles = explode('|', $roles);

            if (!in_array($roleName, ['admin', 'teacher', 'student'])) {
                return response()->json([
                    'status_code' => 403,
                    'errors' => ['Role tidak diizinkan'],
                    'settings' => []
                ], 403);
            }

            if (!in_array($roleName, $requiredRoles)) {
                return response()->json([
                    'status_code' => 403,
                    'errors' => ['Anda tidak memiliki akses untuk fitur ini'],
                    'settings' => []
                ], 403);
            }

            return $next($request);
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->failed(['Token yang anda gunakan tidak valid'], 403);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->failed(['Token anda telah kadaluarsa, silahkan login ulang'], 403);
            } else {
                return response()->failed($e->getMessage());
            }
        }

        return $next($request);
    }
}
