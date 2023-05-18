<div class="p-4">
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <div>
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input wire:model="company.name" class="block mt-1 w-full" type="text" autofocus/>
            @error('company.name')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <div>
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input wire:model="company.email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"/>
            @error('company.email')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <div>
            <x-label for="mobile" value="{{ __('Mobile') }}" />
            <x-input wire:model="company.mobile" id="mobile" class="block mt-1 w-full" type="text" name="mobile" :value="old('mobile')" maxlength='10' />
            @error('company.mobile')
            <span class="text-red-500">{{$message}}</span>
            @enderror
        </div>

        <x-button class="mt-4">
            {{ __('Save') }}
        </x-button>
      </form>
</div>
