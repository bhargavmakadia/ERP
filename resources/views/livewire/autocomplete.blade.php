<div>
    <x-input wire:model="query" class="block mt-1 w-full" type="text"/>
    @if($results)
    <div class="relative">
     <div class="absolute w-full bg-white border">
        @foreach($results as $row)
        <div class="p-2 hover:bg-slate-200 text-gray-800 border-b" wire:click="select('{{$row->id}}')">{{$row->name}}</div>
    @endforeach
    @if($createComponent)
    <div class="p-2 hover:bg-slate-200 text-gray-800 border-b" onclick="openModal('{{$createComponent}}', 'event={{$event}}')">Create New</div>
    @endif
    </div>   
    </div>
    @endif
</div>
