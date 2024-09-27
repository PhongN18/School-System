<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tắt các ràng buộc khóa ngoại
        Schema::disableForeignKeyConstraints();

        // Xóa dữ liệu từ bảng roles
        DB::table('roles')->truncate();

        // Kích hoạt lại các ràng buộc khóa ngoại
        Schema::enableForeignKeyConstraints();

        // Tạo các vai trò mới
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Teacher']);
        Role::create(['name' => 'Parent']);
        Role::create(['name' => 'Student']);
    }
}
