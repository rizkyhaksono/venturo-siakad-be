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

    // Get existing IDs from related tables (you'll need to replace these with actual IDs)
    $classId = '3b0cf27a-6ca5-4125-b69d-9c870a0d6ad0'; // Get from m_class table
    $teacherId = '9ee266cb-d935-4e8e-abe4-f7b9194225e4'; // Get from m_teacher table

    // Get subject IDs (assuming they exist from SubjectSeeder)
    $subjectIds = DB::table('m_subject')->pluck('id')->toArray();

    // Get subject hour IDs (assuming they exist)
    $subjectHourIds = DB::table('m_subject_hour')->pluck('id')->toArray();

    foreach ($days as $day) {
      // Create 8 periods per day
      for ($hour = 0; $hour < 8; $hour++) {
        $schedules[] = [
          'id' => uuid_create(),
          'class_id' => $classId,
          'subject_id' => $subjectIds[$hour % count($subjectIds)], // Rotate through subjects
          'teacher_id' => $teacherId,
          'subject_hour_id' => $subjectHourIds[$hour], // Corresponds to period 1-8
          'day' => $day,
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }
    }

    DB::table('m_subject_schedule')->insert($schedules);
  }
}
