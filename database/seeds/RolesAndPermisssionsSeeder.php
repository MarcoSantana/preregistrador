<?php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'edit documents']);
        Permission::create(['name' => 'delete documents']);
        Permission::create(['name' => 'publish documents']);
        Permission::create(['name' => 'unpublish documents']);
        // Permission::create(['name' => 'create jobs']);
        // Permission::create(['name' => 'see jobs']);

        // create roles and assign created permissions

        $role = Role::create(['name' => 'supervisor']);
        $role->givePermissionTo('edit documents');
        $role->givePermissionTo('unpublish documents');

        $role = Role::create(['name' => 'worker']);
        $role->givePermissionTo(['publish documents', 'unpublish documents']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
