<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $description_long = $this->faker->sentences(rand(1,5), true);
        if (strlen($description_long) > 255) {
            $description_long = substr($description_long, 0, 254) . '.';
        }
        $content = json_encode($this->faker->words(rand(3,255)));
        $isConfirmed = rand(0,1);
        $start_date = $this->faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = null);
        $end_date = $this->faker->dateTimeBetween($startDate = $start_date, $endDate = 'now', $timezone = null);
        $is_published = rand(1,100) < 80 ? 1 : 0;
        $budget = (float) rand(100,999999). '.' . rand(1,99);

        return [
            'state'=> $state,
            'name'=> $name,
            'description'=> $description,
            'description_long'=> $description_long,
            'content'=> $content,
            'isConfirmed'=> $isConfirmed,
            'time'=> $ttttt,
            'start_date'=> $ttttt,
            'created_at'=> $ttttt,
            'end_at'=> $ttttt,
            'amount_decimal'=> $ttttt,
            'amountDouble'=> $ttttt,
            'amount_float'=> $ttttt,
            'members'=> $ttttt,
            'votes'=> $ttttt,
            'teams'=> $ttttt,
            'is_published'=> $ttttt,
        ];
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
