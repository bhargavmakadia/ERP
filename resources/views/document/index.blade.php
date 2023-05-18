<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $type->name }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('create'.$type->name)
                <div class="p-4">
                <a class="float-right mb-3 px-4 p-2 font-semibold text-sm bg-cyan-500 text-white rounded-full shadow-sm" href="{{route('document.create',['type'=>$type->slug])}}">Create {{$type->name}}</a>
                </div>
            @endcan
    
            <div class="clear-both bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
               
              
                <table class="table-auto min-w-full text-sm">
                    <thead class="bg-gray-300">
                        <tr>
                            <th class="text-left border-b p-2">Title</th>
                            <th class="text-left border-b p-2">Number</th>
                            <th class="text-left border-b p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($documents as $document)
                        <tr>
                            <td class="border-b p-2">{{$document->title}}</td>
                            <td class="border-b p-2">{{$document->document_number}}</td>
                            <td class="border-b p-2">
                                @can('edit'.$type->name)
                            <a href="{{route('document.edit',['document'=>$document->id])}}"><x-tabler-edit class="inline-block hover:text-blue-500"/></a>
                            @endcan
                            @can('delete'.$type->name)
                            <a class="text-red-700 cursor-pointer" onclick="getElementById('delete-{{$document->id}}').submit()"><x-tabler-trash class="inline-block"/></a>
                            <form id="delete-{{$document->id}}" action="{{route('document.delete',$document->id)}}" method="POST">
                             @csrf
                                @method('DELETE')
                            </form>
                            @endcan
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-5">
                    {{$documents->links()}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
