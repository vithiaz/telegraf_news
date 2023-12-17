<?php

namespace App\Livewire\Base;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class SearchPage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Binding Variable
    public $search;
    public $PAGE_ID = 1;


    public function mount() {
        if (!session()->has('search')) {
            $this->search = null;

            if (request()->header('Referer')) {
                return redirect(request()->header('Referer'));
            }
            else {
                return abort(404);
            }
        }
        $this->search = session()->get('search');
    }

    public function render()
    {
        $posts = Post::where([
            ['title_slug', 'like', '%'.Str::slug(session()->get('search')).'%'],
        ])->orWhereHas('category', function($q){
            $q->where([
                ['name_slug', 'like', '%'.Str::slug(session()->get('search')).'%'],
            ]);
        })->paginate(12);


        return view('livewire.base.search-page', [
            'posts' => $posts,
            'search_val' => $this->search,
        ]);
    }

    public function translate_date($date, $format='l, j F Y') {
        $date_translate = Carbon::parse($date)->locale(config('app.locale'));
        $date_translate->settings(['formatFunction' => 'translatedFormat']);
        return $date_translate->format($format);
    }



}
