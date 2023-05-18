<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Autocomplete extends Component
{
    public $query, $table, $results = array(), $event, $createComponent;

    public function updatedQuery(){
        if($this->query!=''){
            $this->results = DB::table($this->table)->where('name', 'like', '%'.$this->query.'%')->limit(6)->get();
        }else{
            $this->results = [];
        }
    }
    public function select($rowId){
        $this->emitUp($this->event, $rowId);
        $this->reset(['query','results']);
    }
    public function render()
    {
        return view('livewire.autocomplete');
    }
}
