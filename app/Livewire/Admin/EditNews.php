<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Pages;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\PostCategory;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Intervention\Image\ImageManagerStatic as Image;
use File;

class EditNews extends Component
{
    use WithFileUploads;

    // Data from route
    public $target;
    public $target_id;
    public $target_slug;

    public $post;

    public $pages;
    public $all_categories;
    public $selection_categories;
    public $ImgDeleteState;
    
    // Binding Variable
    public $title;
    public $select_category;
    public $image;
    public $post_date;
    public $location;
    public $body;

    public $db_image;

    protected $rules = [
        'title' => 'required|string',
        'select_category' => 'nullable',
        'image' => 'nullable',
        'post_date' => 'nullable|date',
        'location' => 'nullable|string',
        'body' => 'nullable',
    ];

    public function mount() {
        if (preg_match('/-/', $this->target)) {
            $this->target_id = explode('-', $this->target, 2)[0];
            $this->target_slug = explode('-', $this->target, 2)[1];
        }

        $this->post = Post::where([
            ['id', '=', $this->target_id],
            ['title_slug', '=', $this->target_slug],
        ])->with(['category', 'page'])->first();

        if($this->post == null) {
            return abort(404);
        }

        $this->title = $this->post->title;
        $this->selection_categories = PostCategory::all();
        if ($this->post->category != null) {
            $this->select_category = $this->post->category->id;
        } else {
            $this->select_category = null;
        }
        $this->body = $this->post->body;
        $this->image = $this->post->preview_image;
        $this->db_image = $this->post->preview_image;
        $this->ImgDeleteState = false;
        $this->post_date = $this->post->post_date;
        $this->location = $this->post->location;
    }

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
        $this->ImgDeleteState = true;
    }

    protected $messages = [
        'title.required' => 'Judul harus diisi.',
    ];

    public function render()
    {
        return view('livewire.admin.edit-news')->layout('layouts.admin_app');
    }

    public function validate_data() {
        $this->validate();
        $this->dispatch('toggle-add-post-modal');
    }


    public function empty_image() {
        $this->image = null;
        $this->ImgDeleteState = true;
    }

    public function store_data() {
        if ($this->ImgDeleteState && $this->db_image != null) {
            if (file_exists(public_path() . '/storage/'. $this->db_image)) {
                unlink(public_path() . '/storage/'. $this->db_image);
            }
        }
        $view_img_path = null;
        if ($this->image != null ) {
            if (is_string($this->image)) {
                $view_img_path = $this->image;
            } else {
                $view_img_path = $this->image->store('post_image');
            }

        }

        if ($this->select_category == '0') {
            $this->select_category = null;
        }

        $this->post->title = $this->title;
        $this->post->title_slug = Str::slug($this->title);
        $this->post->body = $this->process_image_on_body();
        $this->post->preview_image = $view_img_path;
        $this->post->post_date = $this->post_date;
        $this->post->category_id = $this->select_category;
        $this->post->location = $this->location;
        $this->post->save();

        return redirect()->route('admin-news-list')->with('message', 'Postingan diubah');
    }


    public function delete_post() {
        // Delete Preview Image on Storage
        $this->delete_img_from_storage($this->post->preview_image);
        
        // Delete Body Image on Storage
        $body_images_src = $this->get_images_src($this->body);
        foreach ($body_images_src as $src) {
            $delete_path = substr($src, strpos($src, 'post_image/'));
            $this->delete_img_from_storage($delete_path);
        }

        $this->post->delete();

        return redirect()->route('admin-news-list')->with('message', 'Postingan dihapus');
    }


    private function process_image_on_body() {
        if ($this->body != null) {

            // Check is there any updated images on body, is any deleted images in body.. delete in storage tho
            $images_on_body = $this->get_images_src($this->body);
            $images_on_db = $this->get_images_src($this->post->body);
            
            if ($images_on_body) {
                foreach ($images_on_db as $image) {
                    if ( !in_array($image, $images_on_body) ) {
                        // Delete This Image File
                        $delete_path = substr($image, strpos($image, 'post_image/'));
                        $this->delete_img_from_storage($delete_path);
                    }
                }
            } else {
                // Delete All Image on DB from this post
                foreach ($images_on_db as $image) {
                    $delete_path = substr($image, strpos($image, 'post_image/'));
                    $this->delete_img_from_storage($delete_path);
                }
            }
                   
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

    private function get_images_src($body) {
        $src_list = [];
        
        if ($body != null) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($body);
            libxml_clear_errors();
            $dom_images = $dom->getElementsByTagName('img');

            foreach ($dom_images as $imageElem) {
                $src = $imageElem->getAttribute('src');
                array_push($src_list, $src);
            }
            
        }
        return $src_list;
    }

    private function delete_img_from_storage($path) {
        if (File::exists(public_path('storage/'.$path))) {
            File::delete(public_path('storage/'.$path));
        }
    }

    public function set_body_content($content) {
        dd($content);
    }

}
