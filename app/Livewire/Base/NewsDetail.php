<?php

namespace App\Livewire\Base;

use App\Models\Post;
use Livewire\Component;
use App\Models\PostComment;
use Illuminate\Support\Carbon;

class NewsDetail extends Component
{
    public $PAGE_ID = 1;

    // Route Binding Variable
    public $target;
    public $target_id;
    public $target_slug;

    // Model Variable
    private $post_model;
    
    // Binding Variable
    public $post;
    public $post_date;
    public $post_location;


    public $visits_count;
    public $comments_count;
    public $recomendation_posts;
    
    public $current_url;
    public $wa_link;
    public $fb_link;

    protected $rules = [
        'comment' => 'nullable',
    ];

    public function mount() {
        // Sharing URL
        $this->current_url = url()->current();
        $url_encode = rawurlencode($this->current_url);

        $this->wa_link = "https://wa.me/?text={$url_encode}";
        $this->fb_link = "https://www.facebook.com/sharer/sharer.php?u={$url_encode}";
        
        $this->init_post_page_data();

        
        // Visit
        $this->post->visit()->hourlyIntervals()->withIp();
        
        
        
        $this->visits_count = $this->post_model->withTotalVisitCount()->first()->visit_count_total;
        $this->comments_count = PostComment::whereHas('post', function($q){
            $q->where('id', '=', $this->target_id);
        })->count();

        

        $this->recomendation_posts = Post::where([
            ['page_id', '=', $this->PAGE_ID],
            ['id', '!=', $this->post->id],
        ])->with('category')->orderBy('created_at', 'DESC')->take(2)->get();

        
    }

    public function render()
    {
        return view('livewire.base.news-detail');
    }

    private function init_post_page_data() {
        if (preg_match('/-/', $this->target)) {
            $this->target_id = explode('-', $this->target, 2)[0];
            $this->target_slug = explode('-', $this->target, 2)[1];
        }

        $this->post_model = Post::where([
            ['id', '=', $this->target_id],
            ['title_slug', '=', $this->target_slug],
        ])->with(['category']);

        $this->post = $this->post_model->first();

        if($this->post == null) {
            return abort(404);
        } else {
            $this->post_date = $this->post->post_date;
            $this->post_location = $this->post->location;
        }

    }

    public function translate_date($date, $format='l, j F Y') {
        $date_translate = Carbon::parse($date)->locale(config('app.locale'));
        $date_translate->settings(['formatFunction' => 'translatedFormat']);
        return $date_translate->format($format);
    }
}
