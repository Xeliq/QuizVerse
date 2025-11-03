<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ogólna wiedza'],
            ['name' => 'Geografia'],
            ['name' => 'Historia'],
            ['name' => 'Nauka'],
            ['name' => 'Sport'],
            ['name' => 'Muzyka'],
            ['name' => 'Filmy i seriale'],
            ['name' => 'Zwierzęta'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
