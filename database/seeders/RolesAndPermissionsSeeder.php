<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'admin.read.*',
            'admin.create.*',
            'admin.edit.*',
            'admin.delete.*',
            'pupil.read.*',
            'pupil.create.*',
            'pupil.edit.*',
            'pupil.delete.*',
            'teacher.read.*',
            'teacher.create.*',
            'teacher.edit.*',
            'teacher.delete.*'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // admin
        Role::create(['name' => 'admin'])
            ->givePermissionTo([
                'admin.read.*',
                'admin.create.*',
                'admin.edit.*',
                'admin.delete.*',
            ]);

        // pupil
        Role::create(['name' => 'pupil'])
            ->givePermissionTo(Permission::all());

        // teacher
        Role::create(['name' => 'teacher'])
            ->givePermissionTo([
                'teacher.read.*',
                'teacher.create.*',
                'teacher.edit.*',
                'teacher.delete.*',
            ]);
    }
}
