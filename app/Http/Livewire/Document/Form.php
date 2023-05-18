<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\Document;
use Auth;
use App\Models\Company;
use App\Models\Item;
use App\Models\Inventory;
use App\Models\Address;
use Illuminate\Validation\Validator;

class Form extends Component
{
    public $document, $type, $company=array(), $items, $dueDate;

    protected $rules = [
        'document.title' => 'required',
        'document.document_number' => 'required',
        'document.document_type_id'=>'required',
        'document.user_id'=>'required',
        'document.buyer_company_id'=>'required',
        'document.seller_company_id'=>'required',
    ];

    protected $listeners = [
        'sellerCompanySelected',
        'buyerCompanySelected', 
        'addItem',
        'removeItem',
        'sellerAddressSelected',
        'billingAddressSelected',
        'deliveryAddressSelected',
        'itemUpdated',
    ];

    public function mount(){

        $this->dueDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +15 day'));
        if(!$this->document){
            $this->document = New document;
            $this->document->document_type_id=$this->type->id;
            $this->document->user_id=Auth::id();
            if($this->type->company_role=='Buyer'){
                //$this->document->buyer_company_id=Auth::user()->company->id;
            }else{
                //$this->document->seller_company_id=Auth::user()->company->id;
            }
        }else{
            if($this->type->payment_involved && $this->document->payment){
                $this->dueDate = $this->document->payment->due_at->format('Y-m-d');
            }
        }
    }

    public function generate()
    {
            $this->withValidator(function (Validator $validator) {
                $validator->after(function ($validator) {
                    if (!isset($this->document->data['seller_address'])) {
                        $validator->errors()->add('seller_address', 'The seller address required.');
                    }
                    if (!isset($this->document->data['billing_address'])) {
                        $validator->errors()->add('billing_address', 'The Billing address required.');
                    }
                    if (!isset($this->document->data['delivery_address'])) {
                        $validator->errors()->add('delivery_address', 'The Delivery address required.');
                    }
                    if (sizeof($this->document->items)==0) {
                        $validator->errors()->add('items', 'Atleast one item required to create document');
                    }
                    if ($this->document->seller_company_id==$this->document->buyer_company_id) {
                        $validator->errors()->add('company', 'Seller and buyer companies must be different');
                    }
                    if($this->document->documentType->inventory_involved){
                        foreach($this->document->items as $item)
                        {
                            if($item->pivot->quantity > $item->inventory()->sum('instock')){
                                $validator->errors()->add('inventory'.$item->sku, 'Stock not available for '.$item->name);
                            }
                        }
                    }
                });
            })->validate();
        $this->document->status = 'Pending';
        $this->document->statuses()->create([
            'name' => $this->document->status,
            'user_id' => Auth::id()
        ]);
        $this->document->save();
        if($this->document->documentType->inventory_involved){
            foreach($this->document->items as $item)
            {
                $invetory = Inventory::where('store_id', 1)->where('item_id', $item->id)->first();
                //Decrement the instock quantity
                $invetory->decrement('instock', intval($item->pivot->quantity));
            }
        }
        session()->flash('alert-success', 'Document generated successfully');
        return redirect()->to(route('document.edit',['document'=>$this->document->id]));
    }
    public function save()
    {
        $validated = $this->validate();
        $this->document->save();
        if($this->document->users->count()==0)
        {
            $this->document->users()->syncWithoutDetaching(Auth::id());
        }

        if($this->type->payment_involved){
            if(!$this->document->payment){
                $this->document->payment()->create([
                    'status' => 'Unpaid',
                    'total_amount' => $this->document->items()->sum('amount'),
                    'paid_amount' => 0,
                    'due_at' => $this->dueDate,
                ]);
            }else{
                $this->document->payment->due_at = $this->dueDate;
                $this->document->payment->save();
            }
        }
        session()->flash('alert-success', 'Document data saved successfully');
        return redirect()->to(route('document.edit',['document'=>$this->document->id]));

        $this->document->refresh();
    }

    public function buyerCompanySelected(Company $company)
    {
        //$this->document->company()->attach($company);
        $this->document->buyer_company_id=$company->id;
        //$validated = $this->validate();
        if($this->document->created_at){
            $this->document->save();
            $this->document->refresh();
        }
    }
    public function sellerCompanySelected(Company $company)
    {
        //$this->document->company()->attach($company);
        $this->document->seller_company_id=$company->id;
        //$validated = $this->validate();
        if($this->document->created_at){
            $this->document->save();
            $this->document->refresh();
        }
    }

    public function removeCompany($companyFieldName)
    {
        $this->company[$companyFieldName] = $this->document->$companyFieldName;
        $this->document->$companyFieldName = null;
    }
    public function oldCompany($companyFieldName)
    {
        $this->document->$companyFieldName=$this->company[$companyFieldName];
    }

    public function addItem(Item $item)
    {
        $data[$item->id]['sku']=$item->sku;
        $data[$item->id]['unit']='';
        $data[$item->id]['price']=$item->price;
        $data[$item->id]['tax']=$item->tax;
        $this->document->items()->syncWithoutDetaching($data);
        $this->document = Document::find($this->document->id);
    }
    public function removeItem(Item $item)
    {
        $this->document->items()->detach($item);
        $this->document = Document::find($this->document->id);
    }
    public function itemUpdated()
    {
        $this->document->refresh();
    }

    public function saveAddress($address, $addressType)
    {
        $addressData['line1']=$address->line1;
        $addressData['line2']=$address->line2;
        $addressData['landmark']=$address->landmark;
        $addressData['pin']=$address->pin;
        $addressData['gstin']=$address->gstin;
        $addressData['city']=$address->city->name;
        $addressData['state']=$address->city->state->name;
        $addressData['country']=$address->city->state->country->name;
        $data = $this->document->data;
        $data[$addressType] = $addressData;
        $this->document->data=$data;
        $this->document->save();
        $this->document->refresh();
        $this->emit('addressChanged');
    }
    public function billingAddressSelected(Address $address)
    {
        $this->saveAddress($address,'billing_address');
    }
    public function sellerAddressSelected(Address $address)
    {
        $this->saveAddress($address,'seller_address');
    }
    public function deliveryAddressSelected(Address $address)
    {
        $this->document->state_id=$address->city->state->id;
        $this->saveAddress($address,'delivery_address');
    } 

    public function render()
    {
        return view('livewire.document.form');
    }
}
