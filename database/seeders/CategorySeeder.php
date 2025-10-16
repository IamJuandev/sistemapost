<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de ejemplo
        $categories = [
            ['name' => 'Electrónicos', 'description' => 'Productos electrónicos y dispositivos'],
            ['name' => 'Ropa', 'description' => 'Ropa y accesorios'],
            ['name' => 'Hogar', 'description' => 'Artículos para el hogar'],
            ['name' => 'Deportes', 'description' => 'Artículos deportivos y recreacionales'],
            ['name' => 'Alimentos', 'description' => 'Productos alimenticios'],
            ['name' => 'Libros', 'description' => 'Libros y materiales de lectura'],
            ['name' => 'Juguetes', 'description' => 'Juguetes y juegos'],
            ['name' => 'Salud', 'description' => 'Productos de salud y cuidado personal'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
