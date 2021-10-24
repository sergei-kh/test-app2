<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Storage;
use Illuminate\Database\Seeder;

class ProductStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all();
        $storages = Storage::all();
        foreach ($products as $product) {
            $idStorage = rand(1, 3);
            $storge = $storages->firstWhere('id', $idStorage);
            $count = rand(1, 10);
            $storge->products()->attach($product, ['count' => $count]);
        }
    }
}
