<?php

namespace App\Http\Controllers;

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
        return view('page.storage');
    }

    public function products()
    {
        return view('page.products');
    }
}
