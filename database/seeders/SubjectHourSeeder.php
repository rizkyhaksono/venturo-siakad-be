<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectHourSeeder extends Seeder
{
  public function run(): void
  {
    DB::table('m_subject_hour')->insert([
      [
        'id' => uuid_create(),
        'start_hour' => 1,
        'start_time' => '07:00:00',
        'end_time' => '07:50:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 2,
        'start_time' => '07:50:00',
        'end_time' => '08:40:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2cd23-9b77-43a0-b84a-79f58c6c8c12',
        'start_hour' => 3,
        'start_time' => '08:40:00',
        'end_time' => '09:30:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2cd3c-7f39-4d00-9c0f-9325de7d6c31',
        'start_hour' => 4,
        'start_time' => '09:30:00',
        'end_time' => '10:20:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2cd89-aa65-4e52-87c5-cb7b9047263c',
        'start_hour' => 5,
        'start_time' => '10:20:00',
        'end_time' => '11:10:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2cda0-67df-4483-94d0-4ced5a9ab282',
        'start_hour' => 6,
        'start_time' => '11:10:00',
        'end_time' => '12:10:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2cdc9-4830-435b-8577-f81315e9cb8b',
        'start_hour' => 7,
        'start_time' => '13:00:00',
        'end_time' => '13:50:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2cde5-52ee-4d2e-b0c8-ac53a5ba4633',
        'start_hour' => 8,
        'start_time' => '13:50:00',
        'end_time' => '14:40:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => '9ea2ce25-a571-41b4-a236-cddb83e5a918',
        'start_hour' => 9,
        'start_time' => '15:15:00',
        'end_time' => '16:05:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ]);
  }
}
