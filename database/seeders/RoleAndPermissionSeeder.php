<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'manage users',
            'manage dinas',
            'manage lembagas',
            'manage jenis perizinan',
            'verify perizinan',
            'apply perizinan',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign created permissions

        // super_admin
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        $role->givePermissionTo(Permission::all());

        // verifikator
        $role = Role::firstOrCreate(['name' => 'verifikator']);
        $role->givePermissionTo([
            'verify perizinan',
            'view dashboard',
        ]);

        // head_of_dept
        $role = Role::firstOrCreate(['name' => 'head_of_dept']);
        $role->givePermissionTo([
            'verify perizinan',
            'view dashboard',
        ]);

        // admin_lembaga
        $role = Role::firstOrCreate(['name' => 'admin_lembaga']);
        $role->givePermissionTo([
            'apply perizinan',
            'view dashboard',
        ]);
    }
}
