<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HobbyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->realText(maxNbChars:30),
            'description' => $this->faker->realText(),
        ];
    }
}
