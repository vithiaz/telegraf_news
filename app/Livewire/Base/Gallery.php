<?php

namespace App\Livewire\Base;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class Gallery extends Component
{
    public $PAGE_ID = 1;
    
    private $gallery_per_load = 12;
    public $load_gallery_amount;

    protected $render_gallery;
    protected $gallery_query;

    public $gallery_search;
    public $search_active;

    public function updatedGallerySearch() {
        if ($this->gallery_search != null) {
            $this->search_active = true;
        }
        else {
            $this->search_active = false;
        }
    }

    public function mount() {
        $this->load_gallery_amount = $this->gallery_per_load;
        $this->search_active = false;
    }

    public function render()
    {
        $this->gallery_query = Post::with('category');

        $galleries = $this->gallery_query->get();

        $render_gallery_filter_count = 0;
        foreach ($galleries as $i) {
            if ($i->preview_image != null) {
                $render_gallery_filter_count++;
            }
        }

        if (!$this->search_active) {
            $this->render_gallery = $galleries->take($this->load_gallery_amount);

            if ($this->load_gallery_amount >= $render_gallery_filter_count) {
                $disable_load_btn = true;
            } else {
                $disable_load_btn = false;
            }
        }
        else {
            $this->render_gallery = $this->gallery_query->where([
                    ['title_slug', 'like', '%'.Str::slug($this->gallery_search).'%'],
                ])->orWhereHas('category', function($q){
                    $q->where([
                        ['page_id', '=', $this->PAGE_ID],
                        ['name_slug', 'like', '%'.Str::slug($this->gallery_search).'%'],
                    ]);
                })->get();
            $disable_load_btn = true;
        }

        return view('livewire.base.gallery', ['galleries' => $this->render_gallery, 'more_post' => $disable_load_btn]);
    }

    public function load_more_post() {
        $this->load_gallery_amount += $this->gallery_per_load;
    }

    public function translate_date($date, $format='l, j F Y') {
        $date_translate = Carbon::parse($date)->locale(config('app.locale'));
        $date_translate->settings(['formatFunction' => 'translatedFormat']);
        return $date_translate->format($format);
    }    

}
