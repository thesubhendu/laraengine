<div class="mx-10 my-5">

    <div>
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                wire:click="goBack">Back</button>
    </div>
    <div class="my-4">
        <h3 class="text-2xl"> Project Name:  {{$project->name}}</h3>
    </div>
    <form wire:submit="create">
        {{ $this->form }}

        <button
            class="m-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
            type="submit">
            Save
        </button>
    </form>

    <x-filament-actions::modals />
</div>
