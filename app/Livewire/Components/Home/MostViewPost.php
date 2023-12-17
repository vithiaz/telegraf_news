<?php

namespace App\Livewire\Components\Home;

use App\Models\Post;
use Livewire\Component;


class MostViewPost extends Component
{
    // Binding Variable
    public $most_view_posts;

    public function mount() {
        $this->most_view_posts = Post::popularAllTime()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.components.home.most-view-post');
    }
}
