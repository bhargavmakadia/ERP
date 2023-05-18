<?php

namespace App\Http\Livewire\Company;

use Livewire\Component;
use App\Models\Company;
use Auth;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;
    public $company, $event, $sameContact, $logo;
    
    protected $rules = [
        'company.name' => 'required|min:6',
        'company.email' => 'required|email',
        'company.mobile'=>'required|digits:10',
        'company.contact_person_name' => 'required|min:6',
        'company.contact_person_email' => 'required|email',
        'company.contact_person_mobile'=>'required|digits:10',
        'company.gstin'=>'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
        'company.user_id'=>'nullable'
    ];
    // ^([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([A-Za-z]){2}?$ another regex for gstin

    public function mount(){
        if(!$this->company){
            $this->company = New Company;
        }
        $this->company->user_id = Auth::id();
    }

    public function contactDetails()
    {
        if($this->sameContact){
            $this->company->contact_person_name = $this->company->name;
            $this->company->contact_person_email = $this->company->email;
            $this->company->contact_person_mobile = $this->company->mobile;
        }
    }

    public function save()
    {
        $validated = $this->validate();
        $idToIgnore = '';
        if($this->company->created_at){
            $idToIgnore = ','.$this->company->id;
        }
        $this->validate([
            'company.email' => 'required|email|unique:companies,email'.$idToIgnore,
        ]);

        $this->contactDetails();
        if($this->logo)
        {
            $this->company->logo = $this->logo->store('logos', 'public');
        }

        $this->company->save();
        session()->flash('message', ' Successfully created.');
        if($this->event){
            $this->emit($this->event, $this->company->id);
            $this->emitUp('closeModal');
        }else{
            return redirect()->to(route('company.index'));
        }
    }
    public function render()
    {
        return view('livewire.company.form');
    }
}
