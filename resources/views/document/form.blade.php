<div>
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <h3 class="font-semibold text-lg">{{ $type->name }} Details</h3>
        <div>
            <x-jet-label for="title" value="{{ __('Title') }}" />
            <x-jet-input wire:model="document.title" class="block mt-1 w-full" type="text" autofocus />
            @error('document.title')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <x-jet-label for="document_number" value="{{ __('Document Number') }}" />
            <x-jet-input  wire:model="document.document_number" class="block mt-1 w-full"  type="text" />
            @error('document.document_number')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <h3 class="font-semibold text-lg mt-5">Seller Details</h3>

        @if($this->type->company_role=='Buyer' || sizeof(Auth::user()->companies)>0)
            <x-tabler-edit class="inline-block" wire:click="removeCompany('seller_company_id')" />
        @endif

        @if($document->seller_company_id)
            Name: {{ $document->seller_company->name }}, Email: {{ $document->seller_company->email }}
            , Mobile: {{ $document->seller_company->mobile }}
            <br/>

            @if($document->created_at)
            <div class="border rounded p-4 text-sm">
                <span class="font-semibold text-base">Seller Address</span><br/>
                @if(isset($document->data['seller_address']))
                    {{ $document->data['seller_address']['line1'] }}<br/>
                    {{ $document->data['seller_address']['line2'] }}<br/>
                    City : 
                    {{ $document->data['seller_address']['city'] }}<br/>
                    State : 
                    {{ $document->data['seller_address']['state'] }}<br/>
                    Country : 
                    {{ $document->data['seller_address']['country'] }}<br/>
                    PIN : 
                    {{ $document->data['seller_address']['pin'] }}<br/>
                @endif
                <br/>
                <a class="text-blue-500 text-sm cursor-pointer" onclick="openModal('company.addresses', 'company={{ $document->seller_company->id }}&event=sellerAddressSelected')">
                    Select Seller Address
                </a>

                @error('seller_address')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            @endif
        @else
            Select Company
            @livewire('autocomplete',['table'=>'companies', 'event'=>'sellerCompanySelected', 'createComponent'=>'company.form'])
            @if(isset($company['seller_company_id']))
                <a wire:click="oldCompany('seller_company_id')" class="text-red-500">
                    <x-tabler-square-x class="inline-block"/>
                    Cancel
                </a>
            @endif
        @endif

            @error('document.seller_company_id')
                <span class="text-red-500">{{ $message }}</span>
            @enderror


        <h3 class="font-semibold text-lg mt-5">Buyer Details</h3>

        <div>
        @if($this->type->company_role=='Seller')
            <x-tabler-edit class="inline-block" wire:click="removeCompany('buyer_company_id')" />
        @endif
        @if($document->buyer_company_id)
            <div class="mb-3">
            Name: {{ $document->buyer_company->name }}, Email: {{ $document->buyer_company->email }}
            , Mobile: {{ $document->buyer_company->mobile }}
            <br/>
                @if($document->created_at)
                <div class="border rounded p-4 text-sm">
                    <span class="font-semibold text-base">Billing Address</span><br/>
                    @if(isset($document->data['billing_address']))
                        {{ $document->data['billing_address']['line1'] }}<br/>
                        {{ $document->data['billing_address']['line2'] }}<br/>
                        City : 
                        {{ $document->data['billing_address']['city'] }}<br/>
                        State : 
                        {{ $document->data['billing_address']['state'] }}<br/>
                        Country : 
                        {{ $document->data['billing_address']['country'] }}<br/>
                        PIN : 
                        {{ $document->data['billing_address']['pin'] }}<br/>
                    @endif
                    <br/>
                    <a class="text-blue-500 text-sm cursor-pointer" onclick="openModal('company.addresses', 'company={{ $document->buyer_company->id }}&event=billingAddressSelected')">
                        Select Billing Address
                    </a>
                    @error('billing_address')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                @endif
            </div>

                

            @if($document->created_at)
            <div class="border rounded p-4 text-sm">
                <span class="font-semibold text-base">Delivery Address</span><br/>
                @if($document->state_id)
                    {{ $document->data['delivery_address']['line1'] }}<br/>
                    {{ $document->data['delivery_address']['line2'] }}<br/>
                    City : 
                    {{ $document->data['delivery_address']['city'] }}<br/>
                    State : 
                    {{ $document->data['delivery_address']['state'] }}<br/>
                    Country : 
                    {{ $document->data['delivery_address']['country'] }}<br/>
                    PIN : 
                    {{ $document->data['delivery_address']['pin'] }}<br/>

                    Place of Supply : {{ $document->place_of_supply->name }}
                @endif
                <br/>
                <a class="text-blue-500 text-sm cursor-pointer" onclick="openModal('company.addresses', 'company={{ $document->buyer_company->id }}&event=deliveryAddressSelected')">
                    Select Delivery Address
                </a>
                @error('delivery_address')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            @endif

        @else
            Select Company
            @livewire('autocomplete',['table'=>'companies', 'event'=>'buyerCompanySelected', 'createComponent'=>'company.form'])
            @if(isset($company['buyer_company_id']))
                <a wire:click="oldCompany('buyer_company_id')" class="text-red-500">
                    <x-tabler-square-x class="inline-block"/>
                    Cancel
                </a>
            @endif
        @endif

            @error('document.buyer_company_id')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div> 

        @if($document->created_at)
            <h3 class="font-semibold text-lg mt-5">Items</h3>


            <div class="my-3">
                <x-jet-label for="items" value="{{ __('To add item type here and select from dropdown') }}" />
                <div class="grid md:grid-cols-2">
                    <div>
                        @livewire('autocomplete',['table'=>'items', 'event'=>'addItem', 'createComponent'=>'item.form'])
                    </div>
                </div>
            </div> 

            <table class="table-auto min-w-full text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="text-left border-b p-2">Item</th>
                        <th class="text-left border-b p-2">HSN/SAC Code</th>
                        <th class="text-left border-b p-2">Quantity</th>
                        <th class="text-left border-b p-2">Unit</th>
                        <th class="text-left border-b p-2">Price</th>
                        <th class="text-left border-b p-2">Tax</th>
                        <th class="text-right border-b p-2">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-slate-500">
                @foreach($document->items as $item)
                    @livewire('document.item',['document'=>$document,'item'=>$item], key($item->id))
                @endforeach
                <tr>
                    <td colspan="7">
                        <div class="text-right p-2">
                            Amount : {{ $document->items()->sum('amount'); }}<br/>
                            IGST : {{ $document->items()->sum('taxes->igst'); }}<br/>
                            SGST : {{ $document->items()->sum('taxes->sgst'); }}<br/>
                            CGST : {{ $document->items()->sum('taxes->cgst'); }}<br/>
                            Total : {{ $document->items()->sum('total_amount'); }}<br/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        @endif

        
        @if($this->document->documentType->payment_involved)
            <div class="mt-3">
                <x-jet-label for="dueDate" value="{{ __('Payment Due Date') }}" />
                <x-jet-input wire:model="dueDate" class="block mt-1 w-full" type="date" />
                @error('dueDate')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
        @endif
       
        <x-jet-button class="mt-4">
            @if($document->created_at) {{ __('Save as Draft') }} @else {{ __('Create') }} @endif
        </x-jet-button>
        @if($document->updated_at) 
            Last saved at 
            {{ $document->updated_at->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s') }} 
        @endif
    </form>

    @if($document->created_at)
        <x-jet-button class="mt-4" wire:click="generate">
             {{ __('Generate') }} {{ $type->name }}
        </x-jet-button>
    @endif

    <x-jet-validation-errors class="mb-4" />

</div>
