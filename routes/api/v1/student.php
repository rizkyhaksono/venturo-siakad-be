<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Student\{
  AuthController,
  ClassesController
};

Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
  Route::prefix('classes')->group(function () {
    Route::get('/', [ClassesController::class, 'index']);
    Route::get('/{class}', [ClassesController::class, 'show']);
  });
});
