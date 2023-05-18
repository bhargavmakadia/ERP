<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\DocumentItem;

class Item extends Component
{
    public $document, $item, $documentItem;

    protected $listeners = ['addressChanged'=>'calculations'];
    protected $rules = [
        'documentItem.quantity' => 'required|integer',
        'documentItem.price' => 'required|numeric',
        'documentItem.tax' => 'required|numeric',
        'documentItem.unit' => 'required',
    ];
    public function mount()
    {
        $this->documentItem = DocumentItem::find($this->item->pivot->id);
        if($this->documentItem->unit==null){ $this->documentItem->unit='Nos'; }
        //dd($this->documentItem->amount);
        if($this->documentItem->amount==null){
            $this->calculations();
        }
    }
    public function updated()
    {
        $validated = $this->validate();
        $this->calculations();
        $this->emitUp('itemUpdated', $this->item->id);
        //$this->document->items()->updateExistingPivot($this->item->id, $validated);
    }
    public function calculations()
    {
        $this->documentItem->amount=intval($this->documentItem->price) * intval($this->documentItem->quantity);
        $tax_amount=(($this->documentItem->tax*(intval($this->documentItem->price) * intval($this->documentItem->quantity)))/100);
        $taxes = array(
            "igst"=>0,
            "sgst"=>0,
            "cgst"=>0,
        );
        if(isset($this->document->data['seller_address']['state']) && $this->document->data['seller_address']['state'] == $this->document->data['delivery_address']['state']){ 
            $taxes['sgst'] = $tax_amount/2;
            $taxes['cgst'] = $taxes['sgst'];
        }else{
            $taxes['igst'] = $tax_amount;
        }
        $this->documentItem->taxes = $taxes;
        $this->documentItem->total_amount = $taxes['igst']+$taxes['sgst']+$taxes['cgst']+$this->documentItem->amount;
        $this->documentItem->save();
    }
    public function remove()
    {
        $this->emitUp('removeItem', $this->item->id);
    }

    public function render()
    {
        return view('livewire.document.item');
    }
}
