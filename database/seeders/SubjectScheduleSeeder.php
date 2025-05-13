<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectScheduleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $schedules = [];

    $classId = 'abbc462f-9667-4a1f-927a-1e520a17f7f5'; // Get from m_class table
    $teacherId = '9ee6bb56-528b-49d0-bf52-f2ca1bc86009'; // Get from m_teacher table

    $subjectIds = DB::table('m_subject')->pluck('id')->toArray();

    $subjectHourIds = DB::table('m_subject_hour')->pluck('id')->toArray();

    foreach ($days as $day) {
      for ($hour = 0; $hour < 8; $hour++) {
        $schedules[] = [
          'id' => uuid_create(),
          'class_id' => $classId,
          'subject_id' => $subjectIds[$hour % count($subjectIds)],
          'teacher_id' => $teacherId,
          'subject_hour_id' => $subjectHourIds[$hour],
          'day' => $day,
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }
    }

    DB::table('m_subject_schedule')->insert($schedules);
  }
}
