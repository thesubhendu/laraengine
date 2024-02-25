<div class="mx-10 my-5">

    <div>
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                wire:click="goBack">Go to Home</button>
    </div>
    <div class="my-4">
        <h3 class="text-2xl"> Project Name:  {{$project->name}}</h3>
    </div>
    <a
        class="mb-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded"
        href="{{route('projects.crud', $project->id)}}"
    >Add Crud</a>
    <button
        class="mb-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded"
        wire:click="generateCode"
    >Generate Code</button>
    <div class="my-3 text-red-700"
         wire:loading
    >
        Generating Code Please Wait...
    </div>

    <ul class="m-3">
        @foreach($cruds as $crud)
            <li class="my-3">
                <span class="font-bold">
                 {{$crud->name}}
                </span>

                <a class="text-white  my-3 bg-sky-500 hover:bg-sky-800 p-2" href="{{route('projects.crud',[$project->id, $crud->id])}}"
                >Edit</a>
            </li>
        @endforeach

    </ul>

    <x-filament-actions::modals />
</div>
