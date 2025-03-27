<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
  public function run(): void
  {
    DB::table('m_user_roles')->insert([
      [
        'id' => uuid_create(),
        'name' => 'Admin',
        'access' => 'classes, subject_hours, subject_schedules, subject, user_roles, users, registrations, class_histories',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'id' => uuid_create(),
        'name' => 'Student',
        'access' => 'classes, subject_hours, subject_schedules, subject',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'id' => uuid_create(),
        'name' => 'Teacher',
        'access' => 'subject_hours, subject_schedules, subject, students',
        'created_at' => now(),
        'updated_at' => now()
      ]
    ]);
  }
}
