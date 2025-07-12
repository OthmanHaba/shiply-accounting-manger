<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\InvoicePrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoicePriceFactory extends Factory
{
    protected $model = InvoicePrice::class;

    public function definition(): array
    {
        return [
            'total_price' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'currency_id' => Currency::factory(),
        ];
    }
}
