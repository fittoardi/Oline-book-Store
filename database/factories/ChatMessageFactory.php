<?php

namespace Database\Factories;

use App\Models\ChatConversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    public function definition(): array
    {
        return ['conversation_id' => ChatConversation::factory(), 'sender_id' => User::factory(), 'message' => fake()->sentence(10), 'attachment' => null, 'read_at' => now()];
    }
}
