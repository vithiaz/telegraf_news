<?php

namespace App\Livewire\Components\Home;

use Livewire\Component;
use App\Models\PostCategory;
use Symfony\Component\Console\Input\Input;

class TopCategories extends Component
{
    // Constructor Variable;
    public $pageId;

    // Binding Variable
    private $top_categories;

    public function mount() {
        $this->top_categories = PostCategory::with('posts')->take(5)->get()->sortByDesc('posts');
    }

    public function render()
    {
        return view('livewire.components.home.top-categories', ['top_categories' => $this->top_categories]);
    }
}
