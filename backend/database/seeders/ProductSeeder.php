<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the products table with sample pizza menu items.
     * These match the original frontend dummy data.
     */
    public function run(): void
    {
        $products = [
            [
                'name'        => 'Pizza Pepperoni',
                'description' => 'Classic pepperoni with extra cheese and our signature sauce.',
                'price'       => 22.00,
                'rating'      => 4.9,
                'image'       => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=400&auto=format&fit=crop',
            ],
            [
                'name'        => 'Pizza Margherita',
                'description' => 'Fresh tomatoes, mozzarella, and basil. A true Italian classic.',
                'price'       => 18.00,
                'rating'      => 4.8,
                'image'       => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=400&auto=format&fit=crop',
            ],
            [
                'name'        => 'Pizza Supreme',
                'description' => 'Loaded with all the fixings: meats, veggies, and extra cheese.',
                'price'       => 26.00,
                'rating'      => 5.0,
                'image'       => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=400&auto=format&fit=crop',
            ],
            [
                'name'        => 'Pizza BBQ Chicken',
                'description' => 'Grilled chicken, BBQ sauce, red onions, and cilantro.',
                'price'       => 24.00,
                'rating'      => 4.7,
                'image'       => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=400&auto=format&fit=crop',
            ],
            [
                'name'        => 'Pizza Hawaiian',
                'description' => 'Ham, pineapple, and mozzarella cheese.',
                'price'       => 20.00,
                'rating'      => 4.5,
                'image'       => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=400&auto=format&fit=crop',
            ],
            [
                'name'        => 'Pizza Veggie',
                'description' => 'Bell peppers, onions, mushrooms, olives, and tomatoes.',
                'price'       => 21.00,
                'rating'      => 4.6,
                'image'       => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=400&auto=format&fit=crop',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
