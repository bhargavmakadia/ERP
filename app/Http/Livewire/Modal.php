<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public $component, $data=array();

    protected $listeners = ['openModal', 'closeModal'];
    public function openModal($component, $params="")
    {
        parse_str($params, $this->data);
        $this->component = $component;
    }
    public function closeModal(){
        $this->reset(['component','data']);
    }
    public function render()
    {
        return view('livewire.modal');
    }
}
