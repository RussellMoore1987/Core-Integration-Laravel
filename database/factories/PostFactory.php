<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = ucwords($this->faker->words(rand(1,4), true));
        $excerpt = $this->faker->sentences(rand(1,5), true);
        if (strlen($excerpt) > 255) {
            $excerpt = substr($excerpt, 0, 254) . '.';
        }
        $content = $this->faker->randomHtml(2,3);
        $post_date = $this->faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = null);
        $is_published = rand(1,100) < 80 ? 1 : 0;

        return [
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
            'post_date' => $post_date,
            'is_published' => $is_published
        ];
    }
}
