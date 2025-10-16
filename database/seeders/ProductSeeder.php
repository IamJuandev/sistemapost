<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan categorías y proveedores antes de crear productos
        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        
        if ($categories->isEmpty() || $suppliers->isEmpty()) {
            $this->command->error('No hay categorías o proveedores disponibles para crear productos.');
            return;
        }
        
        // Crear 20 productos de ejemplo relacionados con categorías y proveedores existentes
        for ($i = 0; $i < 20; $i++) {
            Product::factory()->create([
                'category_id' => $categories->random()->id,
                'supplier_id' => $suppliers->random()->id,
            ]);
        }
    }
}
