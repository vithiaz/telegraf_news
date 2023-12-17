<?php

namespace App\Livewire\Admin;

use App\Models\Pages;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\PostCategory;
use Livewire\WithPagination;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Category extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    
    // Binding Variable
    public $pages;
    public $new_category;
    public $select_page;
    public $delete_id;

    protected $rules = [
        'new_category' => 'required',
    ];

    public function updatedSelectPage () {
        $this->resetPage();
    }

    public function mount() {
        $this->delete_id = null;
        // $this->pages = Pages::all();
        // dd($this->pages);
        // $this->select_page = $this->pages->first()->id;
    }

    public function render()
    {
        $categories = PostCategory::with('posts')->paginate(8);
        return view('livewire.admin.category', ['categories' => $categories])->layout('layouts.admin_app');
    }

    public function validate_category() {
        $this->validate();
        $this->dispatch('toggle-add-category-modal');
    }
    
    public function store_category() {
        $post_category = new PostCategory;

        $generator_rules = [
            'table' => 'post_categories',
            'length' => '8',
            'prefix' => date('ymd'),
            'reset_on_prefix_change' => true,
        ];
        $id_generate = IdGenerator::generate($generator_rules);
    
        $post_category->id = $id_generate;
        $post_category->name = ucwords($this->new_category);
        $post_category->name_slug = Str::slug($this->new_category);
        $post_category->page_id = 1;
        $post_category->save();
        
        $this->dispatch('toggle-add-category-modal');
        $this->new_category = '';

        $msg = ['success' => 'Kategori ditambahkan'];
        $this->dispatch('display-message', $msg);
    }

    public function delete_category() {
        $category = PostCategory::find($this->delete_id);
        if ($category) {
            $category->delete();
            $msg = ['success' => 'Kategori dihapus'];
        } else {
            $msg = ['danger' => 'Kategori Gagal dihapus'];
        }
        
        $this->dispatch('hide-delete-category-modal');
        $this->delete_id = null;
        
        $this->dispatch('display-message', $msg);
    }
}
