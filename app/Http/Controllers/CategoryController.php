<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()->topLevel()->withCount('products')->orderBy('sort_order')->get();

        return view('categories', compact('categories'));
    }
}
