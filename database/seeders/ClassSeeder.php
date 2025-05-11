<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get first study year ID from m_study_year table
    $studyYearId = DB::table('m_study_year')->first()->id;

    $classes = [
      [
        'id' => uuid_create(),
        'name' => 'Kelas 1',
        'study_year_id' => $studyYearId,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Kelas 2',
        'study_year_id' => $studyYearId,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Kelas 3',
        'study_year_id' => $studyYearId,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Kelas 4',
        'study_year_id' => $studyYearId,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Kelas 5',
        'study_year_id' => $studyYearId,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Kelas 6',
        'study_year_id' => $studyYearId,
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ];

    DB::table('m_class')->insert($classes);
  }
}
