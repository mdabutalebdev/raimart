<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(Page $page)
    {
        abort_unless($page->is_active, 404);

        return view('page', compact('page'));
    }
}
