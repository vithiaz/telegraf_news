<?php

namespace App\Livewire\Base;

use DOMDocument;
use App\Models\Post;
use App\Models\Pages;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class Homepage extends Component
{
    protected $post_Query;
    protected $posts;

    private $POPULAR_POST_LIMIT = 12;

    // Binding Variable
    public $popular_post_hero;
    public $popular_post;
    
    public function mount() {
        

        $this->post_Query = Post::with(['category']);
        $this->posts = $this->post_Query->orderBy('created_at', 'DESC')->get();

        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
    }

    public function render()
    {
        $newest_post_hero = $this->posts->first();
        $newest_posts = $this->posts->skip(1)->take(3);
        $weeks_popular_posts = $this->post_Query->popularThisWeek()->orderBy('visit_count_total', 'DESC')->take(8)->get();
        
        // Get more Posts for filling Weekly Posts
        $all_time_popular_posts = [];
        if ($weeks_popular_posts->count() <= $this->POPULAR_POST_LIMIT) {
            $existPost = [];
            
            foreach ($weeks_popular_posts as $posted) {
                array_push($existPost, $posted->id);
            }

            $more_post_limit = $this->POPULAR_POST_LIMIT - $weeks_popular_posts->count();
            $all_time_popular_posts = Post::with(['category'])->popularAllTime()->orderBy('visit_count_total', 'DESC')->whereNotIn('id', $existPost)->take($this->POPULAR_POST_LIMIT)->get();
        }

        return view('livewire.base.homepage', [
            'newest_post_hero' => $newest_post_hero,
            'newest_posts' => $newest_posts,
            'weeks_popular_posts' => $weeks_popular_posts,
            'all_time_popular_posts' => $all_time_popular_posts,
            'page_wallpaper' => '',
            // 'page_wallpaper' => $this->page->wallpaper_img,
        ]);
    }

    public function get_first_paragraph($body) {
        $dom = new DOMDocument;
        $dom->loadHTML($body);
        if ($dom != null) {
            return $dom->getElementsByTagName('p')->item(0)->nodeValue;
        } else {
            return '';
        }
    }

}
