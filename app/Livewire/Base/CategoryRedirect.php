<?php

namespace App\Livewire\Base;

use Livewire\Component;

class CategoryRedirect extends Component
{
   // Route Binding Variable;
   public $targetSlug;

   public function mount() {
       return redirect()->route('category')->with('slug', $this->targetSlug);
   }
}
