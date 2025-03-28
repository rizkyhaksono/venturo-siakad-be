<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectHoursRequest;
use App\Models\SubjectHoursModel;
use Illuminate\Http\JsonResponse;
use Exception;

class SubjectHoursController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param SubjectHoursRequest $request
   * @return JsonResponse
   */
  public function store(SubjectHoursRequest $request): JsonResponse
  {
    try {
      $subjectHours = SubjectHoursModel::create($request->validated());

      return response()->success($subjectHours, 'Subject hours created successfully.');
    } catch (Exception $e) {
      return response()->failed($e->getMessage(), 500);
    }
  }
}
