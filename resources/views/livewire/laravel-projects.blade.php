<div class="m-5">


    <div>
       <button
           class="mb-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
           type="button" wire:click="selectProjectAction">Select Project</button>
    </div>
    <form wire:submit="create">
        {{ $this->form }}

        <button
            class=" my-2 bg-red-500 text-white px-3 rounded-full "
            type="submit">
            Submit
        </button>
    </form>

    <h2 class="text-2xl">All Projects</h2>
    <ul>
        @foreach($projects as $project)
            <li>
                {{$project->name}} , Path - {{$project->path}}

                <button
                    class="mb-3 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-full"
                    wire:click="visitProject({{$project->id}})">Go to project</button>
            </li>
        @endforeach
    </ul>


    <x-filament-actions::modals />
</div>
