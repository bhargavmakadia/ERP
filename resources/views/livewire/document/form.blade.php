<div>
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <h3 class="font-semibold text-lg">Invoice Details</h3>
        <div>
            <x-label for="title" value="{{ __('Title') }}" />
            <x-input wire:model="document.title" class="block mt-1 w-full" type="text" autofocus/>
            @error('document.title')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <div>
            <x-label for="document_number" value="{{ __('Document Number') }}" />
            <x-input wire:model="document.document_number" class="block mt-1 w-full" type="text"/>
            @error('document.document_number')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <h3 class="font-semibold text-lg mt-5">Buyer Details</h3>
        <div>
        @if($document->company)
            <x-tabler-edit class="inline-block" wire:click="removeCompany()"/>Name : {{$company->name}}, Email : {{$company->email}} 
        @else
        @livewire('autocomplete',['table'=>'companies', 'event'=>'companySelected','createComponent'=>'company.form'])
        @endif
        </div>
               <h3 class="font-semibold text-lg mt-5">Items</h3>

               <table class="table-auto min-w-full text-sm">
                <thead class="bg-gray-300">
                    <tr>
                        <th class="text-left border-b p-2">Item</th>
                        <th class="text-left border-b p-2">Quantity</th>
                        <th class="text-left border-b p-2">Price</th>
                        <th class="text-left border-b p-2">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">

               @foreach($document->items as $item)
                <tr>
                    <td class="border-b p-2">{{$item->name}}</td>
                    <td class="border-b p-2"><x-input wire:model="items.quantity" class="block mt-1" type="text"/></td>
                    <td class="border-b p-2"></td>
                    <td class="border-b p-2"></td>
                </tr>
               @endforeach
               </tbody>
          </table>
          <div>
            @if($document->created_at)
        @livewire('autocomplete',['table'=>'items', 'event'=>'addItem','createComponent'=>'item.form'])
        </div>
        @endif
   
   
        <x-button class="mt-4">
            {{ __('Save') }}
        </x-button>
      </form>
</div>
