<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class RoleMiddleware
{
    /**
     * Middleware untuk mengecek permission user ketika melakukan request ke salah satu routes
     * Pada saat membuat sebuah routes, tambahkan hak akses apa yang boleh mengakses endpoint ini
     *
     * Contohnya : Route::get('/users', [UserController::class, 'index'])->middleware(['auth:sanctum','role:user_view']);
     *
     * Routes di atas hanya dapat diakses jika request dilengkapi dengan token Sanctum dan user memiliki akses "user_view"
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        try {
            $user = $request->user();

            if (!$user) {
                throw new AuthenticationException('Unauthenticated.');
            }

            if (!$user->isHasRole($roles)) {
                return response()->json([
                    'status_code' => 403,
                    'errors' => ['Anda tidak memiliki credential untuk mengakses data ini'],
                    'settings' => []
                ], 403);
            }

            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json([
                'status_code' => 401,
                'errors' => ['Token tidak valid atau telah kadaluarsa'],
                'settings' => []
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'errors' => [$e->getMessage()],
                'settings' => []
            ], 500);
        }
    }
}
