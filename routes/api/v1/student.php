<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Student\{
  ClassesController
};

Route::middleware(['auth:sanctum', 'role:student'])->group(function () {
  Route::apiResource('classes', ClassesController::class);
});
