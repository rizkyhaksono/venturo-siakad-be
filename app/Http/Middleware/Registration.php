<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RegistrationsModel;
use Illuminate\Support\Facades\Auth;

class CheckRegistrationStatus
{
  public function handle(Request $request, Closure $next)
  {
    $user = Auth::user();

    if ($user) {
      $registration = RegistrationsModel::where('student_id', $user->id)->first();

      if (!$registration || $registration->status !== 'accepted') {
        return response()->failed([
          'message' => 'Your registration is pending approval or has been rejected'
        ], 403);
      }
    }

    return $next($request);
  }
}
