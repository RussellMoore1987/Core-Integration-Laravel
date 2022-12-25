<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

// ! not working needs to be fixed

class TestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ucwords($this->faker->words(rand(1,5), true));
        $state = $this->faker->stateAbbr();
        $description = $this->faker->sentences(rand(1,5), true);
        if (strlen($description) > 255) {
            $description = substr($description, 0, 254) . '.';
        }
        $descriptionLong = $this->faker->sentences(rand(1,5), true);
        if (strlen($descriptionLong) > 255) {
            $descriptionLong = substr($descriptionLong, 0, 254) . '.';
        }
        $content = json_encode($this->faker->words(rand(3,255)));
        $isConfirmed = rand(0,1);

        return [
            'state'=> $state,
            'name'=> $name,
            'description'=> $description,
            'description_long'=> $descriptionLong,
            'content'=> $content,
            'isConfirmed'=> $isConfirmed,
            'time'=> '',
            'start_date'=> '',
            'created_at'=> '',
            'end_at'=> '',
            'amount_decimal'=> '',
            'amountDouble'=> '',
            'amount_float'=> '',
            'members'=> '',
            'votes'=> '',
            'teams'=> '',
            'is_published'=> '',
        ];
    }
}
