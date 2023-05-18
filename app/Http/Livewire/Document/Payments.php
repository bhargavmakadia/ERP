<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\Document;
use App\Models\PaymentLog;
use App\Models\PaymentMode;

class Payments extends Component
{
    public $document, $paymentLog, $event, $modes, $mark_paid;

    protected $listeners = ['selectedMode'];
    protected $rules = [
        "paymentLog.paid_amount" => "required|numeric",
        "paymentLog.user_id" => "required",
        "paymentLog.paid_at" => "required",
        "paymentLog.payment_id" => "required",
        "paymentLog.payment_mode_id" => "required",
        "paymentLog.reference_number" => "required",
        "paymentLog.comment" => "nullable",
    ];

    public function mount()
    {
        if(!isset($this->document->created_at)){
            $this->document=Document::find($this->document);
        }
        $this->modes = PaymentMode::get();
    }

    public function add()
    {
        $this->paymentLog=new PaymentLog;
        if(!$this->document->payment){
            $this->document->payment()->create([
                'status' => 'Unpaid',
                'total_amount' => $this->document->items()->sum('amount'),
                'paid_amount' => 0,
                'due_at' => date('Y-m-d H:i:s')
            ]);
            $this->document->refresh();
        }
        $this->paymentLog->payment_id = $this->document->payment->id;
        $this->paymentLog->user_id = \Auth::id();
    }
    public function edit(PaymentLog $paymentLog)
    {
        $this->paymentLog=$paymentLog;
    }
    public function cancel()
    {
        $this->reset('paymentLog');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();
        $this->paymentLog->save();
        if($this->mark_paid)
        {
            $this->document->payment->status='Paid';
            $this->document->payment->save();
        }
        $this->reset(['paymentLog','mark_paid']);
        $this->document->refresh();
    }
    public function selectedMode(Mode $Mode)
    {
        $this->paymentLog->payment_mode_id=$Mode->id;
        $this->paymentLog->save();
        $this->document->refresh();
    }

    public function render()
    {
        return view('livewire.document.payments');
    }
}
