<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Storage;

use App\Helpers\Story\StoryHelper;

class PageController extends Controller
{
    public function index()
    {
        return view('page.index');
    }

    public function stockManager()
    {
        return view('page.stock');
    }

    public function storage()
    {
        $storages = Storage::all();
        return view('page.storage.storage', compact('storages'));
    }

    public function storageProducts($id)
    {
        $storage = Storage::with('products')->findOrFail($id);
        return view('page.storage.storage_products', compact('storage'));
    }

    public function storageStories($id)
    {
        $storage = Storage::with(['stories' => function ($query) {
            $query->orderBy('id', 'desc');
            $query->with('products');
        }])->findOrFail($id);
        $stories = StoryHelper::getFormatData($storage->stories);
        return view('page.storage.storage_story', compact('storage', 'stories'));
    }

    public function products()
    {
        $products = Product::all();
        return view('page.products.products', compact('products'));
    }

    public function productsStory($id)
    {
        $product = Product::with('stories')->findOrFail($id);
        $stories = StoryHelper::getFormatProduct($product->stories);
        return view('page.products.product_story', compact('product', 'stories'));
    }
}
