<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $start = Carbon::now();
        // Gọi Seeder tạo
        $this->call(RolesAndPermissionsSeeder::class);
        $faker = Faker::create();

        // Tạo 5 người dùng admin
        for ($i = 1; $i <= 5; $i++) {
            $admin = User::create([
                'name'              => $faker->name,
                'email'             => 'admin' . $i . '@mail.com',
                'password'          => bcrypt('password'),
                'gender'            => $faker->randomElement(['Male', 'Female', 'Other']),
                'phone'             => $faker->unique()->phoneNumber,
                'dateofbirth'       => $faker->dateTimeBetween('-60 years', '-40 years')->format('Y-m-d'),
                'current_address'   => $faker->address,
                'permanent_address' => $faker->address,
                'created_at'        => now(),
            ]);
            $admin->assignRole('Admin');
        }


        // Tạo 50 người dùng Teacher
        for ($i = 1; $i <= 50; $i++) {
            $teacher = User::create([
                'name'              => $faker->name,
                'email'             => 'teacher' . $i . '@mail.com',
                'password'          => bcrypt('password'),
                'profile_picture'   => 'teacher-' . ($i % 25 + 1) . '.jpg',
                'gender'            => $faker->randomElement(['Male', 'Female', 'Other']),
                'phone'             => $faker->unique()->phoneNumber,
                'dateofbirth'       => $faker->dateTimeBetween('-60 years', '-25 years')->format('Y-m-d'),
                'current_address'   => $faker->address,
                'permanent_address' => $faker->address,
                'created_at'        => now(),
            ]);

            $teacher->assignRole('Teacher');
        }

        // Tạo 200 người dùng Parent
        for ($i = 1; $i <= 200; $i++) {
            $parent = User::create([
                'name'              => $faker->name,
                'email'             => 'parent' . $i . '@mail.com',
                'password'          => bcrypt('password'),
                'gender'            => $faker->randomElement(['Male', 'Female', 'Other']),
                'phone'             => $faker->unique()->phoneNumber,
                'dateofbirth'       => $faker->dateTimeBetween('-60 years', '-40 years')->format('Y-m-d'),
                'current_address'   => $faker->address,
                'permanent_address' => $faker->address,
                'created_at'        => now(),
            ]);
            $parent->assignRole('Parent');
        }

        // Tạo 450 người dùng Student
        for ($i = 1; $i <= 450; $i++) {
            $student = User::create([
                'name'              => $faker->name,
                'email'             => 'student' . $i . '@mail.com',
                'password'          => bcrypt('password'),
                'profile_picture'   => 'student-' . ($i % 50 + 1) . '.jpg',
                'gender'            => $faker->randomElement(['Male', 'Female', 'Other']),
                'phone'             => $faker->unique()->phoneNumber,
                'dateofbirth'       => $faker->dateTimeBetween('-18 years', '-16 years')->format('Y-m-d'),
                'current_address'   => $faker->address,
                'permanent_address' => $faker->address,
                'created_at'        => now(),
            ]);
            $student->assignRole('Student');
        }

        // Thêm 18 môn học
        $subjects = [
            'Mathematics', 'Physics', 'Chemistry', 'Biology', 'Informatics',
            'Literature', 'History', 'Geography', 'Civic Education',
            'Foreign Language', 'English', 'French', 'Chinese',
            'Japanese', 'Natural Science', 'Social Science',
            'Technology', 'Physical Education'
        ];

        foreach ($subjects as $index => $subject) {
            DB::table('subjects')->insert([
                'name'          => $subject,
                'slug'          => 'S' . ($index + 1),
                'subject_code'  => 1000 + $index + 1,
                'description'   => 'Subject: ' . $subject,
                'created_at'    => now(),
            ]);
        }

        // Thêm 15 lớp học
        for ($i = 0; $i < 15; $i++) {
            DB::table('classes')->insert([
                'teacher_id'        => $i + 1,  // Giả sử teacher_id là 1-25 tương ứng
                'class_numeric'     => $i + 1,
                'class_name'        => 'Class ' . ($i + 1),
                'class_description' => 'Description for Class ' . ($i + 1),
                'class_room'        => (intdiv($i, 5) + 1) . '0' . ($i % 5 + 1),
                'created_at'        => now(),
            ]);
        }

        // Thêm 50 giáo viên với môn học
        for ($i = 1; $i <= 50; $i++) {
            DB::table('teachers')->insert([
                'user_id'       => $i + 5,          // Giả sử 50 giáo viên có user_id từ 6-55
                'subject_id'    => $i % 18 + 1,     // Mỗi giáo viên dạy một môn học
                'created_at'    => now(),
            ]);
        }

        // Thêm 200 phụ huynh
        for ($i = 1; $i <= 200; $i++) {
            DB::table('parents')->insert([
                'user_id'       => $i + 55,  // Giả sử 200 phụ huynh có user_id từ 56-255
                'created_at'    => now(),
            ]);
        }

        // Thêm 450 học sinh với lớp học và phụ huynh
        for ($i = 1; $i <= 450; $i++) {
            DB::table('students')->insert([
                'user_id'       => $i + 255,            // Giả sử 450 học sinh có user_id từ 256-705
                'parent_id'     => $i % 200 + 1,        // Mỗi học sinh có một phụ huynh tương ứng
                'class_id'      => $i % 15 + 1,         // Mỗi học sinh thuộc một lớp học
                'roll_number'   => $i,
                'created_at'    => now(),
            ]);
        }

        // Add timetable for classes
        for ($classId = 1; $classId <= 15; $classId++) {
            for ($day = 1; $day <= 5; $day++) {
                for ($period = 1; $period <= 8; $period++) {
                    do {
                        $teacher_id = rand(1, 50);
                        $subject_id = rand(1, 18);

                        // Check if this teacher is already assigned to this day and period
                        $exists = DB::table('timetables')->where([
                            ['teacher_id', '=', $teacher_id],
                            ['day', '=', $day],
                            ['period', '=', $period],
                        ])->exists();

                    } while ($exists); // Loop until a unique combination is found

                    // Insert the unique combination into the timetable
                    DB::table('timetables')->insert([
                        'class_id'    => $classId,
                        'day'         => $day,
                        'period'      => $period,
                        'subject_id'  => $subject_id,
                        'teacher_id'  => $teacher_id,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }
            }
        }
        $end = Carbon::now();

        // Calculate duration and log it
        $duration = $start->diffInSeconds($end);
        $this->command->info("Database seeding completed in {$duration} seconds.");
    }
}
