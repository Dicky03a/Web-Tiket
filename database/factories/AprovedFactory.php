<?php

namespace Database\Factories;

use App\Models\Aproved;
use App\Models\Ticket;
use App\Models\Narahubung;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aproved>
 */
class AprovedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::inRandomOrder()->first()?->id ?? Ticket::factory(),
            'narahubung_id' => Narahubung::inRandomOrder()->first()?->id ?? Narahubung::factory(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'notes' => fake()->sentence(),
            'approved_by' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'approved_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
