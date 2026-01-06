<?php

namespace Database\Factories;

use App\Enums\MediaType;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'tmdb_id' => $this->faker->unique()->numberBetween(1, 100000),
            'type' => $this->faker->randomElement(MediaType::cases()),
            'title' => $this->faker->sentence(3),
            'overview' => $this->faker->paragraph(),
            'poster_path' => '/path/to/poster.jpg',
            'backdrop_path' => '/path/to/backdrop.jpg',
            'genres' => json_encode([$this->faker->numberBetween(1, 20)]),
            'rating' => $this->faker->randomFloat(1, 1, 10),
            'release_date' => $this->faker->date(),
            'popularity' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
