<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $costPrice = $this->faker->randomFloat(2, 1, 1000);
        $taxRate = $this->faker->randomFloat(2, 0, 20); // 0% to 20%
        $profitMargin = $this->faker->randomFloat(2, 5, 30); // 5% to 30%
        
        $taxAmount = $costPrice * ($taxRate / 100);
        $profitAmount = $costPrice * ($profitMargin / 100);
        $sellingPrice = $costPrice + $taxAmount + $profitAmount;

        return [
            'name' => $this->faker->word() . ' ' . $this->faker->word(),
            'description' => $this->faker->sentence(),
            'barcode' => $this->faker->unique()->ean13(),
            'cost_price' => $costPrice,
            'tax_rate' => $taxRate,
            'profit_margin' => $profitMargin,
            'selling_price' => $sellingPrice,
            'stock' => $this->faker->numberBetween(0, 100),
            'image_url' => $this->faker->optional()->imageUrl(640, 480, 'products'),
            'category_id' => function () {
                return \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory()->create()->id;
            },
            'supplier_id' => function () {
                return \App\Models\Supplier::inRandomOrder()->first()?->id ?? \App\Models\Supplier::factory()->create()->id;
            },
        ];
    }
}
