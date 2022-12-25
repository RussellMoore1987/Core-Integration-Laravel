<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = ucwords($this->faker->words(rand(1,5), true));
        $roles = $this->faker->randomElement(['UX Designer', 'Designer', 'Project Manager', 'Lead Developer', 'Backend Developer']);
        $client = $this->faker->company;
        $description = $this->faker->sentences(rand(1,5), true);
        if (strlen($description) > 255) {
            $description = substr($description, 0, 254) . '.';
        }
        $content = json_encode($this->faker->words(rand(3,255)));
        $video_link = $this->faker->url;
        $code_link = $this->faker->url;
        $demo_link = $this->faker->url;
        $start_date = $this->faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = null);
        $end_date = $this->faker->dateTimeBetween($startDate = $start_date, $endDate = 'now', $timezone = null);
        $is_published = rand(1,100) < 80 ? 1 : 0;
        $budget = (float) rand(100,999999). '.' . rand(1,99);

        return [
            'title' => $title,
            'roles' => $roles,
            'client' => $client,
            'description' => $description,
            'content' => $content,
            'video_link' => $video_link,
            'code_link' => $code_link,
            'demo_link' => $demo_link,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_published' => $is_published,
            'budget' => $budget,
        ];
    }
}
