<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating roles table');
        $this->truncateRoleTable();
        $this->command->info('Seeding roles table');

        // ===================
        // CREATING ROLES
        // ===================
        $adminRole = new Role();
        $adminRole->name = "admin";
        $adminRole->display_name = "Administrator";
        $adminRole->description  = 'User is the super admin of the system. They own the project.';
        $adminRole->save();

        $userRole = new Role();
        $userRole->name = "user";
        $userRole->display_name = "User";
        $userRole->description  = 'Regular/Default User of the system';
        $userRole->save();

    }

    public function truncateRoleTable() {
        Schema::disableForeignKeyConstraints();
        DB::table('role_user')->truncate();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
