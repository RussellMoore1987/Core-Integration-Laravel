<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tag', function (Blueprint $table) {
            $table->foreignId('project_id');
            $table->foreignId('tag_id');

            $table->primary(['project_id', 'tag_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::create('category_project', function (Blueprint $table) {
            $table->foreignId('project_id');
            $table->foreignId('category_id');

            $table->primary(['project_id', 'category_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('image_project', function (Blueprint $table) {
            $table->foreignId('project_id');
            $table->foreignId('image_id');
            $table->tinyInteger('is_featured_img')->default(0);
            $table->tinyInteger('sort_order')->default(100);

            $table->primary(['project_id', 'image_id']);
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });

        Schema::create('case_study_tag', function (Blueprint $table) {
            $table->foreignId('case_study_id');
            $table->foreignId('tag_id');

            $table->primary(['case_study_id', 'tag_id']);
            $table->foreign('case_study_id')->references('id')->on('case_studies')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::create('case_study_category', function (Blueprint $table) {
            $table->foreignId('case_study_id');
            $table->foreignId('category_id');

            $table->primary(['case_study_id', 'category_id']);
            $table->foreign('case_study_id')->references('id')->on('case_studies')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('case_study_image', function (Blueprint $table) {
            $table->foreignId('case_study_id');
            $table->foreignId('image_id');
            $table->tinyInteger('is_featured_img')->default(0);
            $table->tinyInteger('sort_order')->default(100);

            $table->primary(['case_study_id', 'image_id']);
            $table->foreign('case_study_id')->references('id')->on('case_studies')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id');
            $table->foreignId('tag_id');

            $table->primary(['post_id', 'tag_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignId('post_id');
            $table->foreignId('category_id');

            $table->primary(['post_id', 'category_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('image_post', function (Blueprint $table) {
            $table->foreignId('post_id');
            $table->foreignId('image_id');
            $table->tinyInteger('is_featured_img')->default(0);
            $table->tinyInteger('sort_order')->default(100);

            $table->primary(['post_id', 'image_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });

        Schema::create('resource_tag', function (Blueprint $table) {
            $table->foreignId('resource_id');
            $table->foreignId('tag_id');

            $table->primary(['resource_id', 'tag_id']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::create('category_resource', function (Blueprint $table) {
            $table->foreignId('resource_id');
            $table->foreignId('category_id');

            $table->primary(['resource_id', 'category_id']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('image_resource', function (Blueprint $table) {
            $table->foreignId('resource_id');
            $table->foreignId('image_id');
            $table->tinyInteger('is_featured_img')->default(0);
            $table->tinyInteger('sort_order')->default(100);

            $table->primary(['resource_id', 'image_id']);
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_tag');
        Schema::dropIfExists('category_project');
        Schema::dropIfExists('image_project');
        Schema::dropIfExists('case_study_tag');
        Schema::dropIfExists('case_study_category');
        Schema::dropIfExists('case_study_image');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('image_post');
        Schema::dropIfExists('resource_tag');
        Schema::dropIfExists('category_resource');
        Schema::dropIfExists('image_resource');
    }
}
