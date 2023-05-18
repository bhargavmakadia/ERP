<?php

namespace App\Http\Livewire\Document;
use App\Notifications\DocumentUpdated;
use App\Models\User;

use Livewire\Component;

class Users extends Component
{
    public $document;

    protected $listeners = ['userSelected'];
    public function userSelected($userId)
    {
        $this->document->users()->syncWithoutDetaching($userId);

        $user = User::find($userId);
        $user->notify(new DocumentUpdated($this->document));
        $this->document->refresh();
    }
    public function removeUser($userId)
    {
        $this->document->users()->detach($userId);
        $this->document->refresh();
    }
    public function render()
    {
        return view('livewire.document.users');
    }
}
