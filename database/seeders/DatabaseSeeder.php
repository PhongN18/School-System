<?php

namespace Database\Seeders;

use App\Models\User;

// Đổi App\User thành App\Models\User

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // Tạo người dùng Admin
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('123'),
            'gender' => 'male',
            'phone' => '6969540014',
            'dateofbirth' => '1990-04-11',
            'current_address' => '63 Walnut Hill Drive',
            'permanent_address' => '385 Emma Street',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $user->assignRole('Admin');

        // Tạo người dùng Teacher
        $user2 = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@mail.com',
            'password' => bcrypt('123'),
            'gender' => 'female',
            'phone' => '6969540014',
            'dateofbirth' => '1990-04-11',
            'current_address' => '63 Walnut Hill Drive',
            'permanent_address' => '385 Emma Street',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $user2->assignRole('Teacher');

        // Tạo người dùng Parent
        $user3 = User::create([
            'name' => 'Parent',
            'email' => 'parent@mail.com',
            'password' => bcrypt('123'),
            'gender' => 'female',
            'phone' => '6969540014',
            'dateofbirth' => '1990-04-11',
            'current_address' => '63 Walnut Hill Drive',
            'permanent_address' => '385 Emma Street',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $user3->assignRole('Parent');

        // Tạo người dùng Student
        $user4 = User::create([
            'name' => 'Student',
            'email' => 'student@mail.com',
            'password' => bcrypt('123'),
            'gender' => 'male',
            'phone' => '6969540014',
            'dateofbirth' => '1990-04-11',
            'current_address' => '63 Walnut Hill Drive',
            'permanent_address' => '385 Emma Street',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $user4->assignRole('Student');

        // Thêm môn học
        DB::table('subjects')->insert([
            [
                'name' => 'Math 1',
                'slug' => 'M1',
                'subject_code' => 1234,
                'description' => 'aaaaaa'
            ]
        ]);

        // Thêm thông tin teacher
        DB::table('teachers')->insert([
            [
                'user_id' => $user2->id,
                'subject_id' => 1
            ]
        ]);

        // Thêm thông tin parent
        DB::table('parents')->insert([
            [
                'user_id' => $user3->id,
            ]
        ]);

        // Thêm lớp học
        DB::table('classes')->insert([
            'teacher_id' => 1,
            'class_numeric' => 1,
            'class_name' => 'One',
            'class_description' => 'class one',
            'class_room' => '101'
        ]);

        // Thêm thông tin học sinh
        DB::table('students')->insert([
            [
                'user_id' => $user4->id,
                'parent_id' => 1,
                'class_id' => 1,
                'roll_number' => 1,
            ]
        ]);
    }
}
