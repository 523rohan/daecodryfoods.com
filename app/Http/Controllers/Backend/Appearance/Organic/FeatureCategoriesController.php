<?php

namespace App\Http\Controllers\Backend\Appearance\Organic;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTheme;

class FeatureCategoriesController extends Controller
{
    # construct
    public function __construct()
    {
        $this->middleware(['permission:homepage'])->only('index');
    }

    # top categories
    public function index()
    {
        $theme_base_category_ids = CategoryTheme::where('theme_id', current_theme('organic')->id)->pluck('category_id')->toArray();
        $categories = Category::latest()->whereIn('id', $theme_base_category_ids)->get();

        //  dd($categories);
        return view('backend.pages.appearance.organic.homepage.featureCategories', compact('categories'));
    }
}
