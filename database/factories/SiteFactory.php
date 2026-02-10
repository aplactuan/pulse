<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['operational', 'degraded', 'down']);
        $statusCode = match ($status) {
            'operational' => 200,
            'degraded' => 429,
            'down' => 503,
        };

        return [
            'name' => fake()->words(2, true),
            'url' => fake()->url(),
            'response_time' => fake()->numberBetween(50, 2000),
            'status' => $status,
            'status_code' => $statusCode,
            'last_checked_at' => now()->subMinutes(fake()->numberBetween(1, 120)),
        ];
    }
}
