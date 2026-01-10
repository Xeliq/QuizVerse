<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'General Knowledge'],
            ['name' => 'Geography'],
            ['name' => 'History'],
            ['name' => 'Science'],
            ['name' => 'Sports'],
            ['name' => 'Music'],
            ['name' => 'Movies and Series'],
            ['name' => 'Animals'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
