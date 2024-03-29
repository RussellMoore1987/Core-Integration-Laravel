<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
            $title = ucwords($this->faker->words(random_int(1,5), true));
            $description = $this->faker->sentences(random_int(3,10), true);
            if (strlen($description) > 2500) {
                $description = substr($description, 0, 2500) . '.';
            }
            $sort_order = random_int(1,100) < 70 ? 100 : random_int(1,99);
    
            return [
                'title' => $title,
                'description' => $description,
                'sort_order' => $sort_order
            ];
    }
}
