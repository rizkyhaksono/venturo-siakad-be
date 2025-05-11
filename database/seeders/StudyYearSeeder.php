<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudyYearSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('m_study_year')->insert([
      [
        'id' => uuid_create(),
        'semester' => '2',
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
      ],
      [
        'id' => uuid_create(),
        'semester' => '1',
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
      ],
    ]);
  }
}
