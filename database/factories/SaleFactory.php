<?php

namespace Database\Factories;

use App\Models\Bom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
//            'bom_id' => Bom::all()->random()->id,
//            'date' => $this->faker->dateTimeThisMonth(),
//            'amount'=>rand(100, 1000)
            'description'=>$this->faker->realText(200, 2),
            'date'=>$this->faker->date,
            'invoice_total'=>mt_rand(500, 960),
            'invoice_number'=>Str::random(8),
        ];
    }
}
