<?php

namespace App\Http\Controllers;

use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::active()
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('brands', compact('brands'));
    }
}
