<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api;" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Siakad API Documentation"
 * )
 */

Route::prefix('v1')->group(function () {
    // Public Routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        require __DIR__ . '/api/v1/admin.php';
    });

    // Student Routes
    Route::prefix('student')->group(function () {
        require __DIR__ . '/api/v1/student.php';
    });

    // Teacher Routes
    Route::prefix('teacher')->group(function () {
        require __DIR__ . '/api/v1/teacher.php';
    });
});
