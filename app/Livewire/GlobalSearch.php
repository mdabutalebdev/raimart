<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class GlobalSearch extends Component
{
    public $query = '';

    public function render()
    {
        $results = collect();
        $totalCount = 0;

        if (strlen($this->query) >= 2) {
            $baseQuery = Product::active()->where('name', 'like', '%' . $this->query . '%');
            $totalCount = $baseQuery->count();
            $results = $baseQuery->take(5)->get();
        }

        return view('livewire.global-search', [
            'results' => $results,
            'totalCount' => $totalCount,
        ]);
    }
}
