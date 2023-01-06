<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = ucwords($this->faker->words(random_int(1, 4), true));
        $excerpt = $this->faker->sentences(random_int(1, 5), true);
        if (strlen($excerpt) > 255) {
            $excerpt = substr($excerpt, 0, 254) . '.';
        }
        $content = $this->faker->randomHtml(2, 3);
        $postDate = $this->faker->dateTimeBetween('-10 years', 'now', null);
        $isPublished = random_int(1, 100) < 80 ? 1 : 0;

        return [
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $content,
            'post_date' => $postDate,
            'is_published' => $isPublished
        ];
    }
}
