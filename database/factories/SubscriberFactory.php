<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subscriber>
 */
final class SubscriberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'niche' => fake()->randomElement(['Tech', 'Comedy', 'Finance', 'Fitness', 'Food', 'Lifestyle', 'Gaming', 'Education']),
            'platform' => fake()->randomElement(['YouTube', 'Instagram', 'Both']),
            'language' => fake()->randomElement(['Hindi', 'English', 'Hinglish', 'Tamil', 'Telugu']),
            'whatsapp_number' => fake()->optional(0.4)->numerify('+91##########'),
            'referrer_id' => null,
            'confirm_token' => Str::uuid(),
            'unsubscribe_token' => Str::uuid(),
            'confirmed_at' => null,
            'unsubscribed_at' => null,
            'bounce_reason' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'confirm_token' => null,
            'confirmed_at' => now(),
        ]);
    }

    public function unsubscribed(): static
    {
        return $this->state(fn (array $attributes) => [
            'unsubscribed_at' => now(),
        ]);
    }
}
