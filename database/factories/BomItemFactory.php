<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BomItem>
 */
class BomItemFactory extends Factory
{
    private $i = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = mt_rand(10, 40);
        $rate = mt_rand(100, 400);
        return [
            'Sku'=> 'SKU-'.$this->i++,
            'quantity'=> $quantity,
            'rate'=> $rate,
            'total'=> $quantity * $rate,
        ];
    }
}
