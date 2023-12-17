<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pages;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Model;
use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model implements CanVisit
{
    use HasFactory;
    use HasVisits;

    protected $table = 'posts';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'title_slug',
        'body',
        'view_count',
        'preview_image',
        'post_date',
        'location',
        'post_by',
        'page_id',
        'category_id',
    ];

    public function category()
    {
        return $this->hasOne(PostCategory::class, 'id', 'category_id');
    }

    public function page()
    {
        return $this->hasOne(Pages::class, 'id', 'page_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'post_by');
    }

}
