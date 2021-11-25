<?php

namespace Database\Factories;

use App\Models\SkillType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SkillType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->unique()->words(rand(1,5), true));
        if (strlen($name) > 35) {
            $name = substr($name, 0, 35);
        }

        return [
            'name' => $name,
        ];
    }
}
