<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\Comment;

class Comments extends Component
{
    public $document, $body;
    protected $rules=[
        'body'=>'required'
    ];
    public function add()
    {
        $this->validate();
        $data['body']=$this->body;
        $data['user_id']=\Auth::id();
        $this->document->comments()->create($data);
        $this->document->refresh();
        $this->reset('body');
    }
    public function render()
    {
        return view('livewire.document.comments');
    }
}
