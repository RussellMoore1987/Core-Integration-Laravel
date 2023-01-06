<?php

namespace Database\Factories;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseStudyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CaseStudy::class;

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
        $content = '{"random":93,"random float":93.908,"bool":true,"date":"1999-07-02","regEx":"hello to you","enum":"online","firstname":"Annaliese","lastname":"Erlandson","city":"Doha","country":"Nicaragua","countryCode":"CU","email uses current data":"Annaliese.Erlandson@gmail.com","email from expression":"Annaliese.Erlandson@yopmail.com","array":["Priscilla","Almeta","Paulita","Melina","Randa"],"array of objects":[{"index":0,"index start at 5":5},{"index":1,"index start at 5":6},{"index":2,"index start at 5":7}],"Gabi":{"age":40}}';
        $video_link = $this->faker->url;
        $code_link = $this->faker->url;
        $demo_link = $this->faker->url;
        $start_date = $this->faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now', $timezone = null);
        $end_date = $this->faker->dateTimeBetween($startDate = $start_date, $endDate = 'now', $timezone = null);
        $is_published = random_int(1,100) < 80 ? 1 : 0;

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
            'is_published' => $is_published
        ];
    }
}
