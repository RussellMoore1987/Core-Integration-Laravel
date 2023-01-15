<?php

namespace Database\Seeders;

use App\Models\SkillType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(3)->create();
        \App\Models\Content::factory(50)->create();
        \App\Models\Experience::factory(10)->create();
        
        $caseStudies = \App\Models\CaseStudy::factory(50)->create();
        $projects = \App\Models\Project::factory(50)->create();
        $images = \App\Models\Image::factory(100)->create();
        $posts = \App\Models\Post::factory(100)->create();
        $resources = \App\Models\Resource::factory(100)->create();
        $categories = \App\Models\Category::factory(20)->create();
        $tags = \App\Models\Tag::factory(30)->create();
        $skillTypes = \App\Models\SkillType::factory(2)->create();
        $workHistoryTypes = \App\Models\WorkHistoryType::factory(4)->create();

        \App\Models\WorkHistory::factory(10)->make()->each(function ($workHistory) use ($workHistoryTypes)
        {
            $workHistory->work_history_type_id = $workHistoryTypes->random()->work_history_type_id;
            $workHistory->save();
        });
        \App\Models\Skill::factory(20)->make()->each(function ($skill) use ($skillTypes, $tags)
        {
            $skill->Skill_type_id = $skillTypes->random()->id;
            $skill->tag_id = $tags->random()->id;
            $skill->save();
        });
        
        // Connection tables / pivot tables
        $caseStudies->each(function ($caseStudy) use ($images, $tags, $categories) {
            $caseStudy->images()->attach(
                $images->random(random_int(2, 5))->pluck('id')->toArray(),
                ['sort_order' => random_int(1, 100)]
            );

            $caseStudy->tags()->attach(
                $tags->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $caseStudy->categories()->attach(
                $categories->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $caseStudy->setFeaturedImage($caseStudy->images[0]);
        });

        $projects->each(function ($project) use ($images, $tags, $categories) {
            $project->images()->attach(
                $images->random(random_int(2, 5))->pluck('id')->toArray(),
                ['sort_order' => random_int(1, 100)]
            );

            $project->tags()->attach(
                $tags->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $project->categories()->attach(
                $categories->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $project->setFeaturedImage($project->images[0]);
        });

        $posts->each(function ($post) use ($images, $tags, $categories) {
            $post->images()->attach(
                $images->random(random_int(2, 5))->pluck('id')->toArray(),
                ['sort_order' => random_int(1, 100)]
            );

            $post->tags()->attach(
                $tags->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $post->categories()->attach(
                $categories->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $post->setFeaturedImage($post->images[0]);
        });

        $resources->each(function ($resource) use ($images, $tags, $categories) {
            $resource->images()->attach(
                $images->random(random_int(2, 5))->pluck('id')->toArray(),
                ['sort_order' => random_int(1, 100)]
            );

            $resource->tags()->attach(
                $tags->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $resource->categories()->attach(
                $categories->random(random_int(2, 6))->pluck('id')->toArray()
            );

            $resource->setFeaturedImage($resource->images[0]);
        });
    }
}
