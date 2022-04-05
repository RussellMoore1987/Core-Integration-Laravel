<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test', function (Blueprint $table) {
            $table->id('test_id');
            $table->char('state', 2);
            $table->string('name', 75);
            $table->text('description', 255);
            $table->longText('description_long');
            $table->json('content');
            $table->boolean('isConfirmed');
            $table->time('time', $precision = 0);
            $table->timestamp('start_date');
            $table->dateTime('created_at');
            $table->date('end_at');
            $table->decimal('amount_decimal', $precision = 8, $scale = 2);
            $table->double('amountDouble', 8, 2);
            $table->float('amount_float', 8, 2);
            $table->bigInteger('members');
            $table->integer('votes');
            $table->smallInteger('teams');
            $table->tinyInteger('is_published')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test');
    }
}
