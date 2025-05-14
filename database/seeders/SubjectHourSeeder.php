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
        'id' => uuid_create(),
        'start_hour' => 3,
        'start_time' => '08:40:00',
        'end_time' => '09:30:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 4,
        'start_time' => '09:30:00',
        'end_time' => '10:20:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 5,
        'start_time' => '10:20:00',
        'end_time' => '11:10:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 6,
        'start_time' => '11:10:00',
        'end_time' => '12:10:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 7,
        'start_time' => '13:00:00',
        'end_time' => '13:50:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 8,
        'start_time' => '13:50:00',
        'end_time' => '14:40:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'start_hour' => 9,
        'start_time' => '15:15:00',
        'end_time' => '16:05:00',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ]);
  }
}
