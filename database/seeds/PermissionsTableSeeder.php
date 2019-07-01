<?php

use App\Models\User;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateLaratrustTables();
        $this->command->comment("\n");
        $this->command->info('----------------------------------------------');
        $this->command->info('============== CREATE PERMISSION =============');
        $this->command->info('----------------------------------------------');
        $this->command->comment("\n");
        $roles = config('laravelia.roles');
        $permissions = config('laravelia.permissions');
        $permissions_map = collect(config('laravelia.permissions_maps'));
        $user = User::first();
        foreach($roles as $key => $r){
        	$role = Role::create([
                'name' => $key,
                'display_name' => strtoupper($key),
                'description' => 'Role of ' . str_title($key)
            ]);
            $this->command->info(strtoupper($key) . ' Role created');
            $this->command->comment("\n");
            $array_permissions = [];
            foreach ($r['permissions'] as $module => $value){
                foreach (explode(',', $value) as $p => $perm){
                    $permissionValue = $permissions_map->get($perm);
                    $array_permissions[] = Permission::firstOrCreate([
                    	'index' => $module,
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => str_title($permissionValue) . ' ' . str_title($module),
                        'description' => 'Permission of ' . str_title($permissionValue) . ' ' . str_title($module),
                    ])->id;
                    $this->command->info('Creating Permission '.$permissionValue . '-' . $module.' for '. $module);
                }
            }
            $menus = Menu::select('id as menu_id')->whereIn('en_name', $r['menu'])->get()->toArray();
            $role->menus()->sync($menus);
            $role->permissions()->sync($array_permissions);
            $this->command->comment("\n");
        }
        if($user) $user->attachRole(Role::first());
        // if (!empty($permissions)){
        // 	$permission_all = Permission::select('id as permission_id')->get()->toArray();
        //     $user->permissions()->sync($permission_all);
        // }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        \App\Models\Role::truncate();
        \App\Models\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
