<?php

namespace App\Http\Controllers\Backend\Appearance\Furniture;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTheme;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class FeaturedCollectionProductsController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:homepage'])->only('index');
    }

    # trending products
    public function index()
    {
        $theme_base_category_ids = CategoryTheme::where('theme_id', current_theme('furniture')->id)->pluck('category_id')->toArray();
        $categories = Category::latest()->whereIn('id', $theme_base_category_ids)->get();
        return view('backend.pages.appearance.furniture.homepage.featuredProducts', compact('categories'));
    }

    # get products based on category
    public function getProducts(Request $request)
    {

        $html = '';
        $categories = $request->furniture_featured_product_categories;
        if ($categories) {
            $productIdsFromCategories = ProductCategory::whereIn('category_id', $categories)->pluck('product_id');
            $products = Product::whereIn('id', $productIdsFromCategories)->get();

            $furniture_featured_products = getSetting('furniture_featured_products') != null ? json_decode(getSetting('furniture_featured_products')) : [];

            foreach ($products as $product) {
                if (in_array($product->id, $furniture_featured_products ?? [])) {
                    $html .= '<option value="' . $product->id . '" selected>' . $product->collectLocalization('name') . '</option>';
                } else {
                    $html .= '<option value="' . $product->id . '">' . $product->collectLocalization('name') . '</option>';
                }
            }
        }

        echo json_encode($html);
    }

    public function featureBrandProducts()
    {
        $products = Product::whereHas('themes', function ($query) {
            $query->where('name', 'furniture');
        })->isPublished()->get();

        return view('backend.pages.appearance.furniture.homepage.featuredBrandProducts', compact('products'));
    }
}
