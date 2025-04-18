<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\StudentModel;
use App\Models\SubjectScheduleModel;

class StudentController extends Controller
{
  /**
   * Display a listing of the students.
   * Only for authenticated teachers who have been assigned to a class.
   */
  public function index()
  {
    try {
      $user = auth()->user();

      if (!$user || !$user->teacher) {
        return response()->failed([
          'status' => 'error',
          'message' => 'User is not associated with a teacher account',
        ], 403);
      }

      $teacherId = $user->teacher->id;

      $students = StudentModel::whereHas('classHistory.class.subjectSchedules', function ($query) use ($teacherId) {
        $query->where('teacher_id', $teacherId);
      })->with(['classHistory' => function ($query) {
        $query->latest('entry_date')->limit(1);
      }])->get();

      return response()->success([
        'status' => 'success',
        'data' => $students,
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve students',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Store a newly created student.
   * Only for authenticated teachers who have been assigned to a class.
   */
  public function store(StudentRequest $request)
  {
    try {
      $user = auth()->user();

      if (!$user || !$user->teacher) {
        return response()->failed([
          'status' => 'error',
          'message' => 'User is not associated with a teacher account',
        ], 403);
      }

      $student = StudentModel::create($request->validated());

      return response()->success([
        'status' => 'success',
        'data' => $student,
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to create student',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
