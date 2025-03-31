<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Teacher\{
  StudentsController,
};

Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
  Route::apiResource('students', StudentsController::class);
});
