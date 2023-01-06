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
        $title = ucwords($this->faker->words(random_int(1,5), true));
        $roles = $this->faker->randomElement(['UX Designer', 'Designer', 'Project Manager', 'Lead Developer', 'Backend Developer']);
        $client = $this->faker->company;
        $description = $this->faker->sentences(random_int(1,5), true);
        if (strlen($description) > 255) {
            $description = substr($description, 0, 254) . '.';
        }
        $content = json_encode($this->faker->words(random_int(3,255)));
        $videoLink = $this->faker->url;
        $codeLink = $this->faker->url;
        $demoLink = $this->faker->url;
        $startDate = $this->faker->dateTimeBetween('-10 years', 'now', null);
        $endDate = $this->faker->dateTimeBetween($startDate, 'now', null);
        $isPublished = random_int(1,100) < 80 ? 1 : 0;
        $budget = (float) random_int(100,999999). '.' . random_int(1,99);

        return [
            'title' => $title,
            'roles' => $roles,
            'client' => $client,
            'description' => $description,
            'content' => $content,
            'video_link' => $videoLink,
            'code_link' => $codeLink,
            'demo_link' => $demoLink,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_published' => $isPublished,
            'budget' => $budget,
        ];
    }
}
