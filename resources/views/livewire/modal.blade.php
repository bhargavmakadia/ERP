<div>
    @if($component)
    <div wire:click="closeModal" class="bg-gradient-to-r from-gray-400 to-transparent fixed top-0 right-0 w-full h-full"></div>
    <div class="fixed top-0 right-0 w-full md:w-half lg:w-1/4 bg-white h-full shadow-sm border-l overflow-y-auto">

        <div class="flex items-center justify-between border-b">
            <div class="p-4">
       
            </div>
            <div class="overflow-auto ">
                <a class="text-center text-red-500 cursor-pointer float-right" wire:click="closeModal"><x-tabler-square-x class="m-3"/></a>
            </div>
        </div>
        <div>
            @livewire($component, $data)
        </div>
    </div>
    @endif
</div>
