<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\DocumentType;
use App\Models\Document;
use App\Models\Status;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class Count extends Component
{
    public $types, $statusCount = array();
    public function mount()
    {
        $this->types = DocumentType::withCount('documents')->get();

        //$statuses = Status::get();
        
        $types = DocumentType::where('name', 'Sales Order')->get();
        foreach($types as $type){
            foreach($type->statuses as $status)
            {
                if(Auth::user()->can('View '.$status.' '.$type->name)){
                    $this->statusCount[$type->name][$status] = Document::where('document_type_id', $type->id)
                    ->where('status', $status)->whereHas('users', function (Builder $query) {
                        $query->where('users.id', Auth::id());
                    })->count(); 
                    //->whereDate('created_at', '>' , date('Y-m-d', strtotime('-30 days')))

                }
            }
        }
    }
    public function render()
    {
        return view('livewire.document.count');
    }
}
