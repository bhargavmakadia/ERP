<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;

class Quality extends Component
{
    public $document, $check=false;

    public function add()
    {
        $this->check=true;
    }
    public function save()
    {
        $this->check=false;
    }
    public function render()
    {
        return view('livewire.document.quality-check');
    }
}
