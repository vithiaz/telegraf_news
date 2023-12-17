<?php

namespace App\Livewire\Base;

use DOMDocument;
use Livewire\Component;
use App\Models\PostCategory;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class Category extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Route Binding Variable;

    public $PAGE_ID = 1;
    private $CategoryPerLoad = 6;

    public $load_category_amount;
    public $selected_category;

    public $query_category;

    // Binding variable

    public function mount() {
        $this->query_category = PostCategory::with('posts')->get()->sortByDesc('posts');

        if (session()->has('id')) {
            $this->selected_category = $this->query_category->where('id', '=', session()->get('id'))->first();
        }
        else if (session()->has('slug')) {
            $findCategory =  $this->query_category->where('name_slug', '=', session()->get('slug'));
            if ($findCategory->count() == 0) {
                return abort(404);
            }
            $this->selected_category = $findCategory->first();
        }
        else {
            $this->selected_category = $this->query_category->first();
        }
        $this->load_category_amount = $this->CategoryPerLoad;
    }

    public function render()
    {
        $categories_next_count = $this->query_category->skip($this->load_category_amount)->count();
        
        if ($this->selected_category != null) {
            $render_categories = $this->query_category->where('id', '!=', $this->selected_category->id)->take($this->load_category_amount);
            $posts = $this->selected_category->posts()->paginate(8);
        } else {
            $render_categories = [];
            $posts = [];
        }
        
        return view('livewire.base.category', [
            'categories' => $render_categories,
            'categories_next_count' => $categories_next_count-1,
            'posts' => $posts,
        ]);
    }

    public function load_more_categories() {
        $this->load_category_amount += $this->CategoryPerLoad;
    }

    public function select_category($id) {
        $this->selected_category = $this->query_category->where('id', '=', $id)->first();
        $this->resetPage();
    }

    public function get_first_paragraph($body) {
        $dom = new DOMDocument;
        $dom->loadHTML($body);
        return $dom->getElementsByTagName('p')->item(0)->nodeValue;
    }

    public function translate_date($date, $format='l, j F Y') {
        $date_translate = Carbon::parse($date)->locale(config('app.locale'));
        $date_translate->settings(['formatFunction' => 'translatedFormat']);
        return $date_translate->format($format);
    }

}
