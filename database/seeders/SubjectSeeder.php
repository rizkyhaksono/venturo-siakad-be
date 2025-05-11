<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $subjects = [
      [
        'id' => uuid_create(),
        'name' => 'Bahasa Indonesia',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Matematika',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Ilmu Pengetahuan Alam (IPA)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Ilmu Pengetahuan Sosial (IPS)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Pendidikan Kewarganegaraan (PKn)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Pendidikan Agama',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Pendidikan Jasmani dan Kesehatan (PJOK)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Seni Budaya dan Prakarya (SBdP)',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Bahasa Inggris',
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'id' => uuid_create(),
        'name' => 'Bahasa Daerah',
        'created_at' => now(),
        'updated_at' => now(),
      ],
    ];

    DB::table('m_subject')->insert($subjects);
  }
}
