<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->unique()->words(random_int(1,2), true));
        if (strlen($name) > 35) {
            $name = substr($name, 0, 35);
        } 
        $icon = $this->faker->randomElement(['codepen', 'docker', 'git-alt', 'laravel', 'node', 'npm', 'react', 'php', 'python', 'angular', 'html5', 'css3-alt', 'js-square']);

        return [
            'name' => $name,
            'icon' => $icon
        ];
    }
}
