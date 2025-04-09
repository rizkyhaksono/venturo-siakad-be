<?php

use Illuminate\Support\Facades\Route;


Route::middleware(['auth.api', 'role:student'])->group(function () {
  // 
});
