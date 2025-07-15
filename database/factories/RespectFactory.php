<?php

namespace Database\Factories;

use App\Models\Respect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RespectFactory extends Factory
{
    protected $model = Respect::class;

    public function definition(): array
    {
        return [
            'note' => $this->faker->word(),
            'amount' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
