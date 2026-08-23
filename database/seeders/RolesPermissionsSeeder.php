<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $abilities = [
            'read',
            'write',
            'create',
            'update',
            'delete',
        ];

        $modules = [
            'user management',
            'role management',
            'permission management',
            'product management',
            'category management',
            'brand management',
            'order management',
            'coupon management',
            'marketing management',
            'report management',
            'settings management',
            'content management',
            'financial management',
            'review management',
            'subscriber management',
            'service management',
            'offer management',
            'question management',
        ];

        // Specific actions / permissions
        $specific_permissions = [
            'admin catalouge',
            'product catalouge',
            'order catalouge',
            'add admin',
            'update admin',
            'delete admin',
            'view admin',
            'add role',
            'update role',
            'delete role',
            'details role',
            'update product',
            'delete product',
            'update order',
            'delete order',
        ];

        $all_permissions = [];

        foreach ($modules as $module) {
            $groupName = Str::slug($module);
            foreach ($abilities as $ability) {
                $permissionName = $ability . ' ' . $module;
                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['group_name' => $groupName]
                );
                $all_permissions[] = $permissionName;
            }
        }

        $group_mappings = [
            'admin catalouge' => 'admin-catalouge',
            'product catalouge' => 'product-catalouge',
            'order catalouge' => 'order-catalouge',
            'add admin' => 'admin-catalouge',
            'update admin' => 'admin-catalouge',
            'delete admin' => 'admin-catalouge',
            'view admin' => 'admin-catalouge',
            'add role' => 'admin-catalouge',
            'update role' => 'admin-catalouge',
            'delete role' => 'admin-catalouge',
            'details role' => 'admin-catalouge',
            'update product' => 'product-catalouge',
            'delete product' => 'product-catalouge',
            'update order' => 'order-catalouge',
            'delete order' => 'order-catalouge',
        ];

        foreach ($specific_permissions as $perm) {
            $grp = $group_mappings[$perm] ?? 'general';
            $p = Permission::firstOrCreate(['name' => $perm], ['group_name' => $grp]);
            if (empty($p->group_name)) {
                $p->update(['group_name' => $grp]);
            }
            $all_permissions[] = $perm;
        }

        // Create Super Admin role with ID 1 and assign ALL permissions
        $superAdminRole = Role::where('name', 'super admin')->first();
        if (!$superAdminRole) {
            $superAdminRole = Role::create(['id' => 1, 'name' => 'super admin', 'guard_name' => 'web']);
        } else if ($superAdminRole->id != 1) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \Illuminate\Support\Facades\DB::table('roles')->where('id', $superAdminRole->id)->update(['id' => 1]);
            \Illuminate\Support\Facades\DB::table('model_has_roles')->where('role_id', $superAdminRole->id)->update(['role_id' => 1]);
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->where('role_id', $superAdminRole->id)->update(['role_id' => 1]);
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $superAdminRole = Role::find(1);
        }
        $superAdminRole->syncPermissions(Permission::all());

        // Create Administrator role
        $adminRole = Role::firstOrCreate(['name' => 'administrator']);
        $adminRole->syncPermissions($all_permissions);

        // Assign super admin role to User ID 1 or admin@gmail.com
        $user1 = User::find(1);
        if ($user1) {
            $user1->assignRole('super admin');
        }

        $adminEmailUser = User::where('email', 'admin@gmail.com')->first();
        if ($adminEmailUser) {
            $adminEmailUser->assignRole('super admin');
        }
    }
}
