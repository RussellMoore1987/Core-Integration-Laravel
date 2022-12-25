<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangWorkHistoryTypeIdName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_history_types', function (Blueprint $table) {
            $table->renameColumn('id', 'work_history_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_history_types', function (Blueprint $table) {
            $table->renameColumn('work_history_type_id', 'id');
        });
    }
}
