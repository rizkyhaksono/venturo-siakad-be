<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function index()
    {
        return response()->success([
            'name' => 'Muhammad Rizky Haksono',
            'email' => 'mrizkyhaksono@gmail.com',
        ], 'success get data');
    }
}
