<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KKMSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get subject IDs from the database
    $subjects = [
      'Pendidikan Jasmani dan Kesehatan (PJOK)' => 85,
      'Bahasa Inggris' => 85,
      'Bahasa Daerah' => 81,
      'Ilmu Pengetahuan Alam (IPA)' => 78,
      'Matematika' => 78,
      'Ilmu Pengetahuan Sosial (IPS)' => 81,
      'Pendidikan Agama' => 85,
      'Seni Budaya dan Prakarya (SBdP)' => 85,
      'Pendidikan Kewarganegaraan (PKn)' => 81,
      'Bahasa Indonesia' => 81,
    ];

    $kkmData = [];

    foreach ($subjects as $subjectName => $minScore) {
      // Find the subject ID from the database
      $subject = DB::table('m_subject')->where('name', $subjectName)->first();

      if ($subject) {
        $kkmData[] = [
          'id' => uuid_create(),
          'subject_id' => $subject->id, // This will be the exact UUID from m_subject table
          'min_score' => $minScore,
          'description' => 'KKM for ' . $subjectName,
          'created_at' => now(),
          'updated_at' => now(),
          'deleted_at' => null,
        ];
      }
    }

    // Insert the KKM data
    DB::table('m_kkm')->insert($kkmData);
  }
}
