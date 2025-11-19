<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::paginate(12);
        return view('shop', compact('products'));
    }

    public function about()
    {
        return view('about');
    }

    public function privacy()
    {
        return view('privacy');
    }
}
