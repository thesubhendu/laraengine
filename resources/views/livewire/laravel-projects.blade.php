<div>

    <div class="m-5">
        <h1>All Projects</h1>

        <div>
            {{ $this->selectProjectAction }}
        </div>
        <form wire:submit="create">
            {{ $this->form }}

            <button type="submit">
                Submit
            </button>
        </form>

        <x-filament-actions::modals />
    </div>
</div>
