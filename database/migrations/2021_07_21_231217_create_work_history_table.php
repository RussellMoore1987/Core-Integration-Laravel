<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_history', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->foreignId('work_history_type_id');
            $table->string('date_range', 35)->nullable();
            $table->string('description', 255)->nullable();
            $table->tinyInteger('sort_order')->default(100);
            $table->timestamps();

            $table->foreign('work_history_type_id')->references('id')->on('work_history_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_history');
    }
}
