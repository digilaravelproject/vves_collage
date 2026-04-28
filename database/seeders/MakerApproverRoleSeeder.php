<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MakerApproverRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure permissions exist
        $permissions = [
            'workflow.view',
            'workflow.approve',
            'institution.view',
            'institution.create',
            'institution.edit',
            'institution.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $maker = Role::firstOrCreate(['name' => 'Maker']);
        $approver = Role::firstOrCreate(['name' => 'Approver']);
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);

        // Assign Permissions
        $maker->syncPermissions([
            'institution.view',
            'institution.create',
            'institution.edit',
            'institution.delete',
            'workflow.view', // To see their own pending actions
        ]);

        $approver->syncPermissions([
            'institution.view',
            'workflow.view',
            'workflow.approve',
        ]);
        
        // Super Admin gets everything
        $superAdmin->syncPermissions(Permission::all());
    }
}
