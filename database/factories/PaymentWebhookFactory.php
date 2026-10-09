<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentWebhookFactory extends Factory
{
    public function definition(): array
    {
        return ['payment_id' => Payment::factory(), 'reference' => 'REF-'.fake()->numerify('########'), 'event' => 'payment.created', 'payload' => ['status' => 'pending'], 'signature' => fake()->sha256(), 'processed_at' => null];
    }
}
