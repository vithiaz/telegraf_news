<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Pages;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Intervention\Image\ImageManagerStatic as Image;

class AddNews extends Component
{
    use WithFileUploads;

    public $all_categories;
    public $selection_categories;
    
    // Binding Variable
    public $title;
    public $select_category;
    public $image;
    public $post_date;
    public $location;
    public $body;

    protected $rules = [
        'title' => 'required|string',
        'select_category' => 'nullable',
        'image' => 'nullable|image',
        'post_date' => 'nullable|date',
        'location' => 'nullable|string',
        'body' => 'nullable',
    ];

    protected $messages = [
        'title.required' => 'Judul harus diisi.',
    ];

    public function updatedImage() {
        $validator = Validator::make([ 'image' => $this->image ],
            [
                'image' => 'nullable|image|mimes:png,jpeg,jpg,svg'
            ],
            [
                'image.image' => 'tipe file harus berupa gambar',
                'image.mimes' => 'tipe file harus berupa: jpg, jpeg, png atau svg'
            ]);
        if ($validator->fails()) {
            $this->image = null;
        }
        $validator->validate();
    }

    public function mount() {
        $this->all_categories = PostCategory::all();
        $this->selection_categories = $this->all_categories;
        $this->select_category = null;
    }

    public function render()
    {
        return view('livewire.admin.add-news')->layout('layouts.admin_app');
    }

    public function validate_data() {
        $this->validate();
        $this->dispatch('toggle-add-post-modal');
    }

    public function empty_image() {
        $this->image = null;
    }

    public function store_data() {
        $view_img_path = null;
        if ($this->image != null) {
            $view_img_path = $this->image->store('post_image');
        }

        if ($this->select_category == '0') {
            $this->select_category = null;
        }

        $generator_rules = [
            'table' => 'posts',
            'length' => '12',
            'prefix' => date('ymd'),
            'reset_on_prefix_change' => true,
        ];
        $id_generate = IdGenerator::generate($generator_rules);

        $post = new Post;
        $post->id = $id_generate;
        $post->title = $this->title;
        $post->title_slug = Str::slug($this->title);
        $post->body = $this->process_image_on_body();
        $post->preview_image = $view_img_path;
        $post->post_date = $this->post_date;
        $post->post_by = Auth::user()->id;
        $post->category_id = $this->select_category;
        $post->location = $this->location;
        $post->save();

        return redirect()->route('admin-news-list')->with('message', 'Postingan ditambahkan');
    }

    private function process_image_on_body () {
        if ($this->body != null) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($this->body);
            libxml_clear_errors();
            
            $dom_images = $dom->getElementsByTagName('img');
            foreach ($dom_images as $imageElem) {
                $src = $imageElem->getAttribute('src');
                if (preg_match('/data:image/', $src)) {
                    preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                    $mimetype = $groups['mime'];
                    $file_name = uniqid('img_', true);
                    $file_path = ("storage/post_image/$file_name.$mimetype");
                    
                    //Decode and save image
                    list($type, $src) = explode(';', $src);
                    list(, $src)      = explode(',', $src);
                    $img = base64_decode($src);
                    file_put_contents(public_path($file_path), $img);
                    
                    $new_src = str_replace('admin.', '', asset($file_path));
                    $imageElem->removeAttribute('src');
                    $imageElem->setAttribute('src', $new_src);
                }
            }
            return $dom->saveHTML();  
        }
        return '';
    }

    
}
