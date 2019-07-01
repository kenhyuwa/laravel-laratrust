<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix(__prefix())->group(function(){

	Auth::routes(['register' => false, 'verify' => false]);

	Route::middleware('auth')->group(function(){

		Route::get('/', 'HomeController@index')->name('home');

		/**
		 * REGISTER ROUTES BY MODEL
		 */
		\App\Models\User::routes();
		\App\Models\Menu::routes();
		\App\Models\Role::routes();
		\App\Models\Permission::routes();

	});

});