<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->unique()->words(random_int(1,5), true));
        if (strlen($name) > 35) {
            $name = substr($name, 0, 35);
        }

        return [
            'name' => $name,
        ];
    }
}
