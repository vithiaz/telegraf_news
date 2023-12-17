<?php

namespace App\Livewire\Base;

use DOMDocument;
use App\Models\Post;
use Livewire\Component;
use App\Models\PostCategory;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class NewsPage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $PAGE_ID = 1;
    protected $post_Query;

    // Binding variable
    public $hero_post;
    public $top_categories;

    public function mount() {
        $this->post_Query = Post::with(['category'])->orderBy('created_at', 'desc');

        if ($this->post_Query != null) {
            $this->hero_post = $this->post_Query->first();
        }
        else {
            $this->hero_post = [];
        }
            
        $this->top_categories = PostCategory::with('posts')->take(7)->get()->sortByDesc('posts');
    }

    public function render()
    {
        if ($this->hero_post != null) {
            $post_id_exceptions = $this->hero_post->id;
        } else {
            $post_id_exceptions = null;
        }

        $posts = Post::where([
            ['page_id', '=', $this->PAGE_ID],
            ['id', '!=', $post_id_exceptions],
        ])->with(['category'])->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.base.news-page', ['posts' => $posts]);
    }
    
    public function to_category($id) {
        return redirect()->route('sport-category')->with('id', $id);
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
