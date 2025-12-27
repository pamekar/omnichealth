<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Cart;
use TomatoPHP\FilamentCms\Models\Category;

class ShopComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => '', 'as' => 'category'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%'])
                  ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($this->search) . '%']);
            });
        }

        if ($this->selectedCategory) {
            $query->whereHas('categories', function ($q) {
                $q->where('id', $this->selectedCategory);
            });
        }

        $query->where('is_activated', true);

        $products = $query->paginate(12);

        // Fetch categories that actually have products or just all.
        // Using fully qualified name as per Product model
        $categories = Category::all();

        return view('livewire.shop-component', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
