<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::create([
            'name' => 'Drink',
            'slug' => uniqid() . Str::slug('Drink'),
        ]);

        Category::create([
            'name' => 'Soda',
            'slug' => uniqid() . Str::slug('Soda'),
        ]);

        Category::create([
            'name' => 'Snack',
            'slug' => uniqid() . Str::slug('Snack'),
        ]);
        Category::create([
            'name' => 'Rice',
            'slug' => uniqid() . Str::slug('Rice'),
        ]);
        Category::create([
            'name' => 'Bread',
            'slug' => uniqid() . Str::slug('Bread'),
        ]);
        Category::create([
            'name' => 'Breakfast',
            'slug' => uniqid() . Str::slug('Breakfast'),
        ]);
        Category::create([
            'name' => 'Lunch',
            'slug' => uniqid() . Str::slug('Lunch'),
        ]);
        Category::create([
            'name' => 'Dinner',
            'slug' => uniqid() . Str::slug('Dinner'),
        ]);
        Category::create([
            'name' => 'Sweet',
            'slug' => uniqid() . Str::slug('Sweet'),
        ]);
        Category::create([
            'name' => 'Salad',
            'slug' => uniqid() . Str::slug('Salad'),
        ]);
    }
}
