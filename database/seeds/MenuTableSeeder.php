<?php

use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Menu::truncate();
        $parent = Menu::insert([
            [
            	'id' => Str::orderedUuid()->toString(),
                'parent' => null,
                'queue' => 1,
                'en_name' => 'dashboard',
                'id_name' => 'beranda',
                'icon' => 'fa fa-windows',
                'route' => null,
            ],
            [
            	'id' => Str::orderedUuid()->toString(),
                'parent' => null,
                'queue' => 2,
                'en_name' => 'users',
                'id_name' => 'pengguna',
                'icon' => 'fa fa-users',
                'route' => 'users',
            ],
            [
                'id' => Str::orderedUuid()->toString(),
                'parent' => null,
                'queue' => 3,
                'en_name' => 'access control',
                'id_name' => 'akses kontrol',
                'icon' => 'fa fa-expeditedssl',
                'route' => '#',
            ],
            [
                'id' => Str::orderedUuid()->toString(),
                'parent' => null,
                'queue' => 4,
                'en_name' => 'setting',
                'id_name' => 'pengaturan',
                'icon' => 'fa fa-cog',
                'route' => '#',
            ],
        ]);
        $child = Menu::insert([
            [
                'id' => Str::orderedUuid()->toString(),
                'parent' => self::findParent('access control'),
                'queue' => 1,
                'en_name' => 'roles',
                'id_name' => 'levels',
                'icon' => null,
                'route' => 'roles',
            ],
            [
                'id' => Str::orderedUuid()->toString(),
                'parent' => self::findParent('access control'),
                'queue' => 2,
                'en_name' => 'access',
                'id_name' => 'akses',
                'icon' => null,
                'route' => 'access',
            ],
            [
                'id' => Str::orderedUuid()->toString(),
                'parent' => self::findParent('access control'),
                'queue' => 3,
                'en_name' => 'permissions',
                'id_name' => 'hak akses',
                'icon' => null,
                'route' => 'permissions',
            ],
            [
            	'id' => Str::orderedUuid()->toString(),
                'parent' => self::findParent('setting'),
                'queue' => 1,
                'en_name' => 'menu',
                'id_name' => 'menu',
                'icon' => null,
                'route' => 'menu',
            ],
        ]);
        $secondChild = Menu::insert([
            /**
             * If implement 3'rd child menu
             * @arguments array
             */
        ]);
        $this->command->comment("\n");
        $this->command->info('----------------------------------------------');
        $this->command->info('================= CREATE MENU ================');
        $this->command->info('----------------------------------------------');
        $this->command->comment("\n");
        foreach (Menu::get() as $i => $v):
        	++$i;
        	$this->command->info("$i. " . strtoupper($v->en_name));
        endforeach;
        $this->command->comment("\n");
        $this->command->info('All menu created successfully');
        $this->command->comment("\n");
    }

    public function findParent($name)
    {
        return Menu::whereEnName($name)->first()->id;
    }
}