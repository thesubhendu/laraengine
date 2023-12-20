<div class="mx-10 my-5">

    <div>
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                wire:click="goBack">Go to Home</button>
    </div>
    <div class="my-4">
        <h3 class="text-2xl"> Project Name:  {{$project->name}}</h3>
    </div>
    <a
        class="mb-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-full"
        href="{{route('projects.crud', $project->id)}}"
    >Add Crud</a>
    <button
        class="mb-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-full"
        wire:click="generateCode"
    >Generate Code</button>

    <ul class="m-3">
        @foreach($cruds as $crud)
            <li>
                 {{$crud->name}}

                <a href="{{route('projects.crud',[$project->id, $crud->id])}}"
                >Edit</a>
            </li>
        @endforeach

    </ul>

    <x-filament-actions::modals />
</div>
