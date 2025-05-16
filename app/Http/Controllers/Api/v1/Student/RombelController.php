<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Models\RombelModel;
use App\Models\SubjectScheduleModel;

class RombelController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $user = auth()->user();

    if (!$user || !$user->student) {
      return response()->failed([
        'status' => 'error',
        'message' => 'User is not associated with a student account',
      ], 403);
    }

    $studentId = $user->student->id;

    $rombels = RombelModel::with([
      'class',
      'studyYear',
      'teacher',
      'student',
      'subjectSchedules'
    ])
      ->whereHas('student', function ($query) use ($studentId) {
        $query->where('student_id', $studentId);
      })
      ->get();

    return response()->json([
      'status' => 'success',
      'message' => 'Rombel list retrieved successfully',
      'data' => $rombels
    ]);
  }

  /**
   * Display the schedule for a specific rombel.
   */
  public function showSchedule($rombelId)
  {
    $user = auth()->user();

    if (!$user || !$user->student) {
      return response()->failed([
        'status' => 'error',
        'message' => 'User is not associated with a student account',
      ], 403);
    }

    $studentId = $user->student->id;

    // Check if student belongs to this rombel
    $isStudentInRombel = RombelModel::where('id', $rombelId)
      ->where('student_id', $studentId)
      ->exists();

    if (!$isStudentInRombel) {
      return response()->json([
        'status' => 'error',
        'message' => 'You do not have access to this rombel schedule',
      ], 403);
    }

    // Get the rombel with its details
    $rombel = RombelModel::with([
      'class',
      'studyYear',
      'teacher',
    ])->findOrFail($rombelId);

    // Get all subject schedules for this rombel
    $schedules = SubjectScheduleModel::with([
      'subject',
      'teacher',
      'subjectHour',
      'class'
    ])
      ->where('rombel_id', $rombelId)
      ->orderBy('day')
      ->orderBy('subject_hour_id')
      ->get();

    // Group schedules by day for better organization
    $groupedSchedules = $schedules->groupBy('day');

    // Format the final response
    $formattedSchedule = [];
    foreach ($groupedSchedules as $day => $daySchedules) {
      $formattedSchedule[$day] = $daySchedules->map(function ($schedule) {
        return [
          'id' => $schedule->id,
          'subject' => $schedule->subject ? $schedule->subject->name : null,
          'teacher' => $schedule->teacher ? $schedule->teacher->name : null,
          'time' => $schedule->subjectHour ? [
            'start_time' => $schedule->subjectHour->start_time,
            'end_time' => $schedule->subjectHour->end_time,
          ] : null,
        ];
      });
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Rombel schedule retrieved successfully',
      'data' => [
        'rombel' => [
          'id' => $rombel->id,
          'name' => $rombel->name,
          'class' => $rombel->class ? $rombel->class->name : null,
          'study_year' => $rombel->studyYear ? [
            'year' => $rombel->studyYear->year,
            'semester' => $rombel->studyYear->semester
          ] : null,
          'homeroom_teacher' => $rombel->teacher ? $rombel->teacher->name : null,
        ],
        'schedule' => $formattedSchedule
      ]
    ]);
  }
}
