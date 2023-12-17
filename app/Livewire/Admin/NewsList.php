<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Pages;
use Livewire\Component;
use Livewire\WithPagination;

class NewsList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    
    public function updatedSelectPage () {
        $this->resetPage();
    }

    public function mount() {
    }

    public function render()
    {
        $posts = Post::with(['category', 'user'])->paginate(8);
        return view('livewire.admin.news-list', ['posts' => $posts])->layout('layouts.admin_app');
    }

}
