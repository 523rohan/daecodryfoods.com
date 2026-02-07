<?php

namespace App\Http\Controllers\Backend\Appearance\Organic;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTheme;

class BestDealProductsController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:homepage'])->only('index');
    }

    # best deal products
    public function index()
    {
        $theme_wise_product_ids = ProductTheme::where('theme_id', current_theme('default')->id)->pluck('product_id')->toArray();
        $products = Product::isPublished()->whereIn('id', $theme_wise_product_ids)->get();
        // dd($products);
        return view('backend.pages.appearance.organic.homepage.bestDealProducts', compact('products'));
    }
}
