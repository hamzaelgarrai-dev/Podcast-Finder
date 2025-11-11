<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
// inRandomOrder()->first()->id
        return [
            "titre"=>fake()->title(),
            "description"=>fake()->paragraph(),
            "image_url"=>fake()->imageUrl(),
            "category"=>fake()->randomElement(["dev","funny","sport"]),
            "user_id"=>User::where("role", "animateur")->get(["id"])->random(),
        ];
    }
}
