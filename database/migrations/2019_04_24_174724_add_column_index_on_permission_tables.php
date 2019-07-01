<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIndexOnPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'index')) {
            Schema::table('permissions', function(Blueprint $table) {
                $table->string('index')->index()->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('permissions', 'index')) {
            Schema::table('permissions', function(Blueprint $table) {
                $table->dropColumn('index');
            });
        }
    }
}
