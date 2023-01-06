<?php

namespace Database\Factories;

use App\Models\WorkHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = ucwords($this->faker->words(random_int(1,4), true));

        $startDate =  date($format = 'M Y', $max = 2021);
        $endDate = date($format = 'M Y', $max = 2021);
        $date_range = $startDate . ' - ' . $endDate;

        $description = $this->faker->sentences(random_int(1,5), true);
        if (strlen($description) > 255) {
            $description = substr($description, 0, 254) . '.';
        }
        $sort_order = random_int(1,100) < 70 ? 100 : random_int(1,99);

        return [
            'title' => $title,
            'date_range' => $date_range,
            'description' => $description,
            'sort_order' => $sort_order
        ];
    }
}
