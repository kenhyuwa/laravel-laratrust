<?php 

return [
	"models" => [
		"users" => App\Models\User::class,
		"menu" =>  App\Models\Menu::class,
		"roles" =>  App\Models\Role::class,
		"permissions" =>  App\Models\Permission::class,
	],
	"seeds" => [
		"users" => UsersTableSeeder::class,
		"menu" => MenuTableSeeder::class,
		"permissions" => PermissionTableSeeder::class
	],
	"themes" => [
		[
			"version" => "v1",
			"name" => "AdminLTE Admin Template",
			"description" => "Powered of Bootstrap framework",
			"images" => "v1.png",
			"status" => true
		],
		[
			"version" => "v2",
			"name" => "BULKIT Admin Template",
			"description" => "Powered of Bulma framework",
			"images" => "v2.png",
			"status" => false
		],
	],
	"localization" => [
		"id_ID" => "id", 
		"en_EN" => "en", 
	],
	"default_role" => "owner",
	"roles" => [
		"owner" => [
			"menu" => [
				"dashboard",
				"setting",
				"menu",
				"users",
				"access control",
				"roles",
				"access",
				"permissions"
			],
			"permissions" => [
				"dashboard" => "i",
				"users" => "i,c,sh,st,e,u,d",
				"menu" => "i,st,e,u",
				"roles" => "i,c,sh,st,e,u,d",
				"access" => "i,st",
				"permissions" => "i,st"
			]
		],
	],
	"permissions" => [
		// "dashboard",
		// "application",
		// "menu",
		// "sales",
		// "profile",
	],
	"permissions_maps" => [
		"i" => "index",
		"c" => "create",
		"sh" => "show",
		"st" => "store",
		"e" => "edit",
		"u" => "update",
		"d" => "destroy",
	],
];