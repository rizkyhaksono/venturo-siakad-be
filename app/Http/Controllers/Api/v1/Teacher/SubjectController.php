<?php

namespace  App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SubjectModel;

class SubjectController extends Controller
{
  /**
   * This method is used to retrieve the list of subjects for the authenticated teacher.
   * It includes the subject name who teaches the subject, and the number of students in each class.
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

      $subjects = SubjectModel::with(['studyYear'])
        ->where('teacher_id', $teacherId)
        ->get();

      return response()->success([
        'status' => 'success',
        'data' => $subjects,
      ]);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subjects',
        'error' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified subject.
   * Only for authenticated teachers who have been assigned to a class.
   * This method retrieves the subject details for the authenticated teacher.
   * @param string $id The ID of the subject to retrieve.
   */
  public function show(string $id)
  {
    try {
      $user = auth()->user();

      if (!$user || !$user->teacher) {
        return response()->failed([
          'status' => 'error',
          'message' => 'User is not associated with a teacher account',
        ], 403);
      }

      $subject = SubjectModel::with(['studyYear'])
        ->where('id', $id)
        ->firstOrFail();

      return response()->success([
        'status' => 'success',
        'data' => $subject,
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Subject not found',
      ], 404);
    } catch (Exception $e) {
      return response()->failed([
        'status' => 'error',
        'message' => 'Failed to retrieve subject',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
}
