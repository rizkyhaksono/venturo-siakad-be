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
                                'access' => 'admin.view, admin.create, admin.update, admin.delete',
                                'created_at' => now(),
                                'updated_at' => now()
                        ],
                        [
                                'id' => uuid_create(),
                                'name' => 'Student',
                                'access' => 'student.view, student.create, student.update',
                                'created_at' => now(),
                                'updated_at' => now()
                        ],
                        [
                                'id' => uuid_create(),
                                'name' => 'Teacher',
                                'access' => 'teacher.view, teacher.create, teacher.update, teacher.delete',
                                'created_at' => now(),
                                'updated_at' => now()
                        ]
                ]);
        }
}
