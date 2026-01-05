<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;   
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15',
                'description' => 'Latest Apple iPhone',
                'price' => 79999,
                'image' => 'products/iphone.jpg',
                'stock_quantity' => 20,
                'alert_qty' => 5,
            ],
            [
                'name' => 'Samsung Galaxy S25',
                'description' => 'Samsung Galaxy S25 Ultra 5G AI Smartphone (Titanium Whitesilver, 12GB RAM, 256GB Storage), 200MP Camera, S Pen Included, Long Battery Life',
                'price' => 65999,
                'image' => 'products/samsung.jpg',
                'stock_quantity' => 10,
                'alert_qty' => 3,
            ],
            [
                'name' => 'OnePlus Nord 5',
                'description' => 'OnePlus Nord 5 | Snapdragon 8s Gen 3 | Stable 144FPS Gaming | Dual 50MP Flagship Camera | Powered by OnePlus AI | 8GB + 256GB | Dry Ice',
                'price' => 33999,
                'image' => 'products/oneplus.jpg',
                'stock_quantity' => 50,
                'alert_qty' => 10,
            ],
            [
                'name' => 'iQOO Neo 10R 5G',
                'description' => 'iQOO Neo 10R 5G,iQOO Neo 10R 5G  Raging Blue, 8GB RAM, 256GB Storage | Snapdragon 8s Gen 3 Processor ',
                'price' => 28999 ,
                'image' => 'products/iqOO.jpg',
                'stock_quantity' => 50,
                'alert_qty' => 10,
            ],
            [
                'name' => 'Redmi A4 5G',
                'description' => 'Redmi A4 5G (Sparkle Purple, 4GB RAM, 128GB Storage) | Segment Largest 6.88in 120Hz | 50MP Dual Camera | 18W Fast Charging | Charger in The Box',
                'price' => 9000,
                'image' => 'products/redmi.jpg',
                'stock_quantity' => 50,
                'alert_qty' => 10,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
