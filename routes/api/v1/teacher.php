<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth.api', 'role:teacher'])->group(function () {
  // 
});
