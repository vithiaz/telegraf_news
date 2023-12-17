<?php

namespace App\Livewire\Components\NewsDetail;

use Livewire\Component;
use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;

class Comments extends Component
{
    // Constructor Variable
    public $postId;
    
    // Binding Variable
    public $comment;
    public $delete_id;

    protected $rules = [
        'comment' => 'nullable',
    ];

    protected $listeners = [
        'refreshSelf' => '$refresh',
    ];

    public function mount() {
        $this->comment = '';
    }

    public function render()
    {
        $post_comments = PostComment::with([
            'post',
            'user',
        ])->whereHas('post', function($q){
            $q->where('id', '=', $this->postId);
        })->orderBy('created_at', 'desc')->get();     

        return view('livewire.components.news-detail.comments', ['comments' => $post_comments]);
    }

    public function store_comment() {
        $this->validate();
        $new_comment = new PostComment;
        $new_comment->comment = $this->comment;
        $new_comment->post_id = $this->postId;
        $new_comment->user_id = Auth::user()->id;
        $new_comment->save();

        $this->comment = '';
        $this->dispatch('refreshSelf');
    }

    public function delete_comment() {
        $delete_comment = PostComment::find($this->delete_id);

        if (($delete_comment->user_id == Auth::user()->id) || (Auth::user()->user_type == 1)) {
            $delete_comment->delete();
            $msg = ['success' => 'Kategori dihapus'];
            $this->dispatch('display-message', $msg);
        } else {
            return abort(403);
        }

        $this->dispatch('hide-delete-comment-modal');
        $this->delete_id = null;
    }
}
