<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\Teacher\StudentResource;
use App\Models\StudentModel;

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
      }])->paginate(10);

      return response()->json([
        'status' => 'success',
        'data' => StudentResource::collection($students),
        'meta' => [
          'current_page' => $students->currentPage(),
          'last_page' => $students->lastPage(),
          'per_page' => $students->perPage(),
          'total' => $students->total(),
        ],
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
