<?php

namespace Database\Factories;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "titre"=>fake()->title(),
            "description"=>fake()->paragraph(),
            "fichier_audio"=>fake()->url(),
            "podcast_id"=>Podcast::all(['id'])->random()
        ];
    }
}
