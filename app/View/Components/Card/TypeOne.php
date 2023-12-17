<?php

namespace App\View\Components\Card;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class TypeOne extends Component
{
    public $image;
    public $category;
    public $categorySlug;
    public $user;
    public $date;
    public $title;
    public $titleSlug;
    public $postId;


    public function __construct(
        $image,
        $category,
        $categorySlug,
        $date,
        $title,
        $titleSlug,
        $postId
    )
    {
        $this->image = $image;
        $this->category = $category;
        $this->categorySlug = $categorySlug;
        $this->date = $date;
        $this->title = $title;
        $this->titleSlug = $titleSlug;
        $this->postId = $postId;
    }

    public function render()
    {
        return view('components.card.type-one');
    }
}
