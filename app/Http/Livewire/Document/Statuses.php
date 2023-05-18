<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\Document;
use App\Models\Status;
use Auth;
use App\Notifications\DocumentUpdated;

class Statuses extends Component
{
    public $document, $statuses=array(), $comment_read;

    protected $rules = [
        "document.status" => "required",
        "comment_read" => "accepted",
    ];
    protected $messages = [
        "comment_read.accepted" => 'Need to read all comments and tick this',
    ];

    public function mount()
    {
        //$statuses = Status::get();
        foreach($this->document->documentType->statuses as $status)
        {
            if(Auth::user()->can('Update '.$status.' '.$this->document->DocumentType->name))
            {
                $this->statuses[]=$status;
            }
        }
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();
        $this->document->statuses()->create([
            'name' => $this->document->status,
            'user_id' => Auth::id()
        ]);
        $this->document->save();
        foreach($this->document->users as $user){
            if($user->id != Auth::id()){
                $user->notify(new DocumentUpdated($this->document));
            }
        }
        $this->document->users()->syncWithoutDetaching(\Auth::id());

        session()->flash('alert-success', 'Status changed to '.$this->document->status);
        return redirect()->to(route('document.edit',['document'=>$this->document->id]));

        $this->document->refresh();
    }

    public function render()
    {
        return view('livewire.document.statuses');
    }
}
