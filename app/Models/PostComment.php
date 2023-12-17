<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostComment extends Model
{
    use HasFactory;

    protected $table = 'post_comments';

    protected $fillable = [
        'comment',
        'post_id',
        'user_id',
    ];

    public function post() {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
