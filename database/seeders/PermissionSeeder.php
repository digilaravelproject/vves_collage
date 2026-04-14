<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Cross-DB compatible truncation
        Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        // Permissions ki list
        $permissions = [
            // User & Role Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',
            'manage permissions',

            // Content Management (Pages & Menus)
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'manage menus',
            'homepage.setup',
            'media.manage',

            // Institution Management
            'institution.view',
            'institution.create',
            'institution.edit',
            'institution.delete',
            'institution.approve',
            'staff.manage',
            'result.manage',
            'pta.manage',

            // Announcements & Events
            'view announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',
            'publish announcements',
            'view events',
            'create events',
            'edit events',
            'delete events',
            'manage event categories',

            // Gallery
            'view gallery',
            'upload gallery images',
            'delete gallery images',
            'manage gallery categories',

            // Academic & Info Sections
            'manage academic calendar',
            'manage testimonials',
            'approve testimonials',
            'manage trust sections',
            'manage why choose us',
            'popup.manage',
            'banner.manage',

            // Workflow & Security
            'workflow.view',
            'workflow.approve',
            'bypass_checker',

            // General Settings
            'manage settings',
            'view admin dashboard'
        ];

        // Har permission ko create karein
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- Role & Permission Assignment ---
        $roleAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
        $roleChecker = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Checker']);
        $roleStaff = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Staff']);

        // 1. Admin gets EVERYTHING
        $roleAdmin->syncPermissions(Permission::all());

        // 2. Checker gets View & Approval rights
        $roleChecker->syncPermissions([
            'view admin dashboard',
            'workflow.view',
            'workflow.approve',
            'institution.view',
            'view pages',
            'view announcements',
            'view events',
            'view gallery',
        ]);

        // 3. Staff gets Creation rights but NO approval/bypass
        $roleStaff->syncPermissions([
            'view admin dashboard',
            'view pages',
            'create pages',
            'edit pages',
            'institution.view',
            'institution.edit',
            'view announcements',
            'create announcements',
            'edit announcements',
            'view events',
            'create events',
            'edit events',
            'view gallery',
            'upload gallery images',
        ]);
    }
}
