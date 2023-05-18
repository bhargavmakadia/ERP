<?php

namespace App\Http\Livewire\Company;

use Livewire\Component;
use App\Models\Company;
use App\Models\Address;
use App\Models\City;

class Addresses extends Component
{
    public $company, $event, $address;

    protected $listeners = ['selectedCity'];

    protected $rules = [
        "address.line1" => "required",
        "address.line2" => "nullable",
        "address.landmark" => "nullable",
        "address.pin" => "required|digits:6",
        "address.gstin" => "nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/",
        "address.city_id" => "required",
        "address.company_id" => "required",
    ];

    public function mount()
    {
        if(!isset($this->company->created_at)){
            $this->company=Company::find($this->company);
        }
    }
    public function add()
    {
        $this->address=new Address;
        $this->address->company_id=$this->company->id;
    }
    public function edit(Address $address)
    {
        $this->address=$address;
    }
    public function cancel()
    {
        $this->reset('address');
    }
    public function select($addressId)
    {
        $this->emit($this->event, $addressId);
        $this->emitUp('closeModal');
    }
    public function save()
    {
        $this->validate();
        $this->address->save();
        $this->reset('address');
        $this->company->refresh();
    }
    public function selectedCity(City $city)
    {
        $this->address->city_id=$city->id;
        if($this->address->created_at)
        {
            $this->address->save();
            $this->address->refresh();
        }
    }
    public function removeCity()
    {
        $this->address->city_id=null;
    }
    public function render()
    {
        return view('livewire.company.addresses');
    }
}
