<?php

namespace Database\Factories;

use App\Models\WorkHistoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkHistoryTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkHistoryType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->unique()->words(random_int(1,3), true));
        if (strlen($name) > 35) {
            $name = substr($name, 0, 35);
        } 
        $icon = random_int(1, 100) > 80 ? 'graduation-cap' : null;

        return [
            'name' => $name,
            'icon' => $icon
        ];
    }
}
