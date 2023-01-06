<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $image_name = $this->faker->unique()->word . '.png';
        $alt = $this->faker->words(random_int(1,10), true);
        if (strlen($alt) > 255) {
            $alt = substr($alt, 0, 253) . '.';
        }

        return [
            'image_name' => $image_name,
            'alt' => $alt
        ];
    }
}
