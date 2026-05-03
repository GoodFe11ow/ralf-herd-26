<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Notebook',
                'image' => 'https://placehold.co/600x400?text=Notebook',
                'price' => 4.99,
                'description' => 'Compact notebook for daily notes and study tasks.',
            ],
            [
                'name' => 'Backpack',
                'image' => 'https://placehold.co/600x400?text=Backpack',
                'price' => 39.90,
                'description' => 'Lightweight backpack with space for books and a laptop.',
            ],
            [
                'name' => 'Desk Lamp',
                'image' => 'https://placehold.co/600x400?text=Desk+Lamp',
                'price' => 24.50,
                'description' => 'LED desk lamp with adjustable angle and soft light.',
            ],
            [
                'name' => 'Water Bottle',
                'image' => 'https://placehold.co/600x400?text=Water+Bottle',
                'price' => 12.00,
                'description' => 'Reusable water bottle for school, work, or travel.',
            ],
            [
                'name' => 'Wireless Mouse',
                'image' => 'https://placehold.co/600x400?text=Wireless+Mouse',
                'price' => 18.75,
                'description' => 'Comfortable wireless mouse for everyday computer use.',
            ],
            [
                'name' => 'Keyboard',
                'image' => 'https://placehold.co/600x400?text=Keyboard',
                'price' => 29.99,
                'description' => 'Full-size keyboard suitable for study and office work.',
            ],
            [
                'name' => 'Headphones',
                'image' => 'https://placehold.co/600x400?text=Headphones',
                'price' => 49.00,
                'description' => 'Over-ear headphones with clear sound and soft cushions.',
            ],
            [
                'name' => 'USB Drive',
                'image' => 'https://placehold.co/600x400?text=USB+Drive',
                'price' => 9.50,
                'description' => 'Portable storage device for documents and media files.',
            ],
            [
                'name' => 'Study Chair',
                'image' => 'https://placehold.co/600x400?text=Study+Chair',
                'price' => 89.00,
                'description' => 'Ergonomic chair designed for comfortable long study sessions.',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
