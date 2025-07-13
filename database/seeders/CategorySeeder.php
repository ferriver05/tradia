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
        $categories = [
            'Electrónica',
            'Libros',
            'Hogar',
            'Ropa',
            'Juguetes',
            'Deportes',
            'Herramientas',
            'Arte',
            'Instrumentos',
            'Salud',
            'Automotriz',
            'Bebés',
            'Mascotas',
            'Oficina',
            'Otros',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
