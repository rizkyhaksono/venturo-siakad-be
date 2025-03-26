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
        'access' => 'Can access all features',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'id' => uuid_create(),
        'name' => 'Student',
        'access' => 'Can access student features',
        'created_at' => now(),
        'updated_at' => now()
      ],
      [
        'id' => uuid_create(),
        'name' => 'Teacher',
        'access' => 'Can access teacher features',
        'created_at' => now(),
        'updated_at' => now()
      ]
    ]);
  }
}
