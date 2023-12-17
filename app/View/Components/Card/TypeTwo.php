<?php

namespace App\View\Components\Card;

use Closure;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class TypeTwo extends Component
{
    public $image;
    public $category;
    public $category_slug;
    public $user;
    public $date;
    public $title;
    public $title_slug;
    public $postId;

    public function __construct(
        $image,
        $category,
        $date,
        $title,
        $postId
    )
    {
        $this->image = $image;
        $this->category = $category;
        $this->category_slug = Str::slug($category);
        $this->date = $date;
        $this->title = $title;
        $this->title_slug = Str::slug($title);
        $this->postId = $postId;
    }

    public function render()
    {
        return view('components.card.type-two');
    }
}
