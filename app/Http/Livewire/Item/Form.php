<?php

namespace App\Http\Livewire\Item;

use Livewire\Component;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $item, $event;

    protected $rules = [
        'item.name' => 'required',
        'item.price' => 'numeric',
        'item.tax'=>'numeric|max:30',
        'item.sku'=>'required',
        'item.currency'=>'required',
        'item.description'=>'required',
        'item.hsn_code'=>'nullable',
        'item.sac_code'=>'nullable',
    ];

    public function mount(){
        if(!$this->item){
            $this->item = New Item;
        }
        //$this->currencies = DB::table('countries')->select('currency','currency_symbol')->distinct()->get();
    }

    public function save()
    {
        $validated = $this->validate();
        $this->item->save();
        session()->flash('message', ' Successfully saved.');
        if($this->event){
            $this->emit($this->event, $this->item->id);
            $this->emitUp('closeModal');
        }else{
            return redirect()->to(route('item.index'));
        }
    }
    public function render()
    {
        return view('livewire.item.form');
    }
}
