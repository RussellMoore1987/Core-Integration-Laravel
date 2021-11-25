<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Content::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $content = '{"random":93,"random float":93.908,"bool":true,"date":"1999-07-02","regEx":"hello to you","enum":"online","firstname":"Annaliese","lastname":"Erlandson","city":"Doha","country":"Nicaragua","countryCode":"CU","email uses current data":"Annaliese.Erlandson@gmail.com","email from expression":"Annaliese.Erlandson@yopmail.com","array":["Priscilla","Almeta","Paulita","Melina","Randa"],"array of objects":[{"index":0,"index start at 5":5},{"index":1,"index start at 5":6},{"index":2,"index start at 5":7}],"Gabi":{"age":40}}';
        $name = ucwords($this->faker->unique()->words(rand(1,5), true));
        if (strlen($name) > 50) {
            $name = substr($name, 0, 50);
        }

        return [
            'name' => $name,
            'content' => $content
        ];
    }
}
