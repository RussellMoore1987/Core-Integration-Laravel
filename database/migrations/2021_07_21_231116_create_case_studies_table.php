<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 75);
            $table->string('roles', 50)->nullable();
            $table->string('client', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->json('content')->nullable();
            $table->string('video_link', 255)->nullable();
            $table->string('code_link', 255)->nullable();
            $table->string('demo_link', 255)->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_studies');
    }
}
