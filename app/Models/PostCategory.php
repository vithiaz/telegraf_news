<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCategory extends Model
{
    use HasFactory;

    protected $table = 'post_categories';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'name_slug',
        'page_id',
    ];


    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

}
