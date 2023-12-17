<?php

namespace App\Livewire\Components\Home;

use DOMDocument;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Carbon;

class UpdatePost extends Component
{
    // Constructor Variable
    public $class;
    
    private $PostPerLoad = 8;

    public $load_post_amount;
    protected $post_Query;

    // Binding Variable


    public function mount() {
        $this->load_post_amount = $this->PostPerLoad;

        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
    }

    public function render()
    {
        $query = Post::with(['category']);
        // $load_posts = $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->orderBy('created_at', 'DESC')->take($this->load_post_amount*2)->get();
        $load_posts = $query->orderBy('created_at', 'DESC')->take($this->load_post_amount*2)->get();
        
        $render_posts = $load_posts->take($this->load_post_amount);

        if ($this->load_post_amount < 24) {
            $posts_next_count = $load_posts->skip($this->load_post_amount)->count();
        } else {
            $posts_next_count = 0;
        }
        return view('livewire.components.home.update-post', ['posts' => $render_posts, 'posts_next_count' => $posts_next_count]);
    }
}
