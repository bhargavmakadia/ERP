<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Company') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <x-validation-errors class="mb-4" />

                @livewire('company.form',['company'=>$company])

              <form action="{{route('company.update',['company'=>$company->id])}}" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{$company->name}}" autofocus/>
                    @error('name')
                    <span class="text-red-500">{{$message}}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{$company->email}}"/>
                    @error('email')
                    <span class="text-red-500">{{$message}}</span>
                    @enderror
                </div>

                <div>
                    <x-label for="mobile" value="{{ __('Mobile') }}" />
                    <x-input id="mobile" class="block mt-1 w-full" type="text" name="mobile" value="{{$company->mobile}}" maxlength='10' />
                    @error('mobile')
                    <span class="text-red-500">{{$message}}</span>
                    @enderror
                </div>

                <x-button class="mt-4">
                    {{ __('Save') }}
                </x-button>
              </form>
            </div>
        </div>
    </div>
</x-app-layout>
