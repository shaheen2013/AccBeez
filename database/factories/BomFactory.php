<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bom>
 */
class BomFactory extends Factory
{
    private $i = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> 'Example bom '.$this->i++,
            'invoice_total'=> mt_rand(100, 800),
            'company_id' => rand(1, 5)
        ];
    }
}
