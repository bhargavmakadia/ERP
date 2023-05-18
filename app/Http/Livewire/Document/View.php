<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;

class View extends Component
{
    public $document;
    
    public function render()
    {
        return view('livewire.document.view');
    }
}
