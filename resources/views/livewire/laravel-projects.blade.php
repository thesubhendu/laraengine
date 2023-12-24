<div class="m-5">


    <div>
       <button
           class="mb-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
           type="button" wire:click="selectProjectAction">Select Project</button>
    </div>


    <h2 class=" border-y-2 py-2 text-2xl font-bold mb-3">All Projects</h2>
    <ul>
        @foreach($projects as $project)
            <li>
                <span class="font-bold text-xl">
                    {{$project->path}}
                </span>

                <button
                    class=" ml-5 mb-3 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 rounded "
                    wire:click="visitProject({{$project->id}})">Open project</button>

                <button
                    class="mb-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded"
                    wire:confirm="Are you sure you want to delete this?"
                    wire:click="deleteProject({{$project->id}})">Delete</button>
            </li>
        @endforeach
    </ul>


    <x-filament-actions::modals />
</div>
