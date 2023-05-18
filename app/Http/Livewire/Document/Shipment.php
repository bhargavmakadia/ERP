<?php

namespace App\Http\Livewire\Document;

use Livewire\Component;
use App\Models\Shipment as Ship;
use Seshac\Shiprocket\Shiprocket;
use App\Models\ShippingMode;

class Shipment extends Component
{
    public $document, $shipment, $expectedDispatchDate, $actualDispatchDate, $modes;

    protected $rules = [
        "shipment.document_id" => "required",
        "shipment.shipping_mode_id" => "required",
        "expectedDispatchDate" => "date",
        "actualDispatchDate" => "nullable|date",
        "shipment.awb" => "nullable",
        "shipment.length" => "nullable|numeric",
        "shipment.breadth" => "nullable|numeric",
        "shipment.height" => "nullable|numeric",
        "shipment.weight" => "nullable|numeric",
        "shipment.payment_method" => "nullable",
    ];

    public function mount()
    {
        if($this->document->shipment){
            $this->shipment = $this->document->shipment;
            $this->expectedDispatchDate = $this->shipment->expected_dispatch_date->format('Y-m-d');
            if($this->shipment->actual_dispatch_date){
                $this->actualDispatchDate = $this->shipment->actual_dispatch_date->format('Y-m-d');
            }
        }else{
            $this->shipment=new Ship;
            $this->shipment->document_id = $this->document->id;
        }
        $this->modes = ShippingMode::get();
    }
    public function save()
    {
        $this->validate();
        $this->shipment->expected_dispatch_date = $this->expectedDispatchDate;
        $this->shipment->actual_dispatch_date = $this->actualDispatchDate;
        $this->shipment->save();
        $this->document->users()->syncWithoutDetaching(\Auth::id());
        session()->flash('alert-success', 'Shipment details updated');
        return redirect()->to(route('document.edit',['document'=>$this->document->id]));
    }

    public function test(){
        $token =  Shiprocket::getToken();

        //to get orders
        $orderDetails = [
            // refer above url for required parameters 
            'per_page'=>20,
        ];
        $response =  Shiprocket::order($token)->getOrders($orderDetails);
        dd($response);

        //to create order
        $orderItems = array();
        foreach($this->document->documentItems as $this->documentItem)
        {
            $orderItems[] = [
                "name" => $this->documentItem->sku,
                "sku" => $this->documentItem->sku,
                "units" => $this->documentItem->quantity,
                "selling_price" => $this->documentItem->price,
                "discount" => 0,
                "tax" => $this->documentItem->taxes['igst']+$this->documentItem->taxes['cgst']+$this->documentItem->taxes['sgst'],
                "hsn" => 63079090,
            ];
        }
        $orderDetails = [
            "order_id" => $this->document->id,
            "order_date" => $this->document->created_at->setTimezone('Asia/Kolkata')->format('Y-m-d H:i'),
            "pickup_location" => "Andheri",
            "billing_customer_name" => $this->document->buyer_company->name,
            "billing_last_name" => "",
            "billing_address" => $this->document->data['billing_address']['line1'],
            "billing_address_2" => $this->document->data['billing_address']['line2'],
            "billing_city" =>  $this->document->data['billing_address']['city'],
            "billing_pincode" =>  $this->document->data['billing_address']['pin'],
            "billing_state" => $this->document->data['billing_address']['state'],
            "billing_country" => $this->document->data['billing_address']['country'],
            "billing_email" => $this->document->buyer_company->email,
            "billing_phone" => $this->document->buyer_company->mobile,
            "shipping_is_billing" => 0,
            "shipping_customer_name" => $this->document->buyer_company->name,
            "shipping_last_name" => "",
            "shipping_address" => $this->document->data['delivery_address']['line1'],
            "shipping_address_2" => $this->document->data['delivery_address']['line2'],
            "shipping_city" => $this->document->data['delivery_address']['city'],
            "shipping_pincode" => $this->document->data['delivery_address']['pin'],
            "shipping_country" => $this->document->data['delivery_address']['country'],
            "shipping_state" => $this->document->data['delivery_address']['state'],
            "shipping_email" => $this->document->buyer_company->email,
            "shipping_phone" => $this->document->buyer_company->mobile,
            "order_items" => $orderItems,
            "payment_method" => "Prepaid",
            "sub_total" => $this->document->items()->sum('total_amount'),
            "length" => 5.5,
            "breadth" => 4,
            "height" => 4,
            "weight" => 2,
            "customer_gstin" => $this->document->buyer_company->gstin,
            "invoice_number" => $this->document->document_number,
        ];
        $token =  Shiprocket::getToken();
        $response =  Shiprocket::order($token)->create($orderDetails);
        dd($response);
    }

    public function render()
    {
        return view('livewire.document.shipment');
    }
}
