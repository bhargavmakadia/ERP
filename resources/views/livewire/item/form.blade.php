<div class="p-4">
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <div>
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input wire:model="item.name" class="block mt-1 w-full" type="text" autofocus/>
            @error('item.name')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <div>
            <x-label for="price" value="{{ __('Price') }}" />
            <x-input wire:model="item.price" class="block mt-1 w-full" type="text"/>
            @error('item.price')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>
        
        <div>
            <x-label for="description" value="{{ __('description') }}" />
            <x-input wire:model="item.description" class="block mt-1 w-full" type="text"/>
            @error('item.description')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <div>
            <x-label for="tax" value="{{ __('tax') }}" />
            <x-input wire:model="item.tax" class="block mt-1 w-full" type="text"/>
            @error('item.tax')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <div>
            <x-label for="hsn_code" value="{{ __('hsn_code') }}" />
            <x-input wire:model="item.hsn_code" class="block mt-1 w-full" type="text"/>
            @error('item.hsn_code')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

     <div>
            <x-label for="sac_code" value="{{ __('sac_code') }}" />
            <x-input wire:model="item.sac_code" class="block mt-1 w-full" type="text"/>
            @error('item.sac_code')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <x-button class="mt-4">
            {{ __('Save') }}
        </x-button>
      </form>
</div>
