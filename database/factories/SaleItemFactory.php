<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    private $i= 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Sku'=>'SKU-'.$this->i++,
            'name'=>'Sale item - '.$this->i++,
            'rate'=>mt_rand(50, 99),
            'unit'=>'Example unit '.$this->i++,
            'quantity'=>mt_rand(50, 99),
            'total'=>mt_rand(500, 999),
        ];
    }
}
