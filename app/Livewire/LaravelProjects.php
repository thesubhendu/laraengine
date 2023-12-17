<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Native\Laravel\Dialog;
use Filament\Actions;


class LaravelProjects extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use Actions\Concerns\InteractsWithActions;

    public ?array $data = [];

    public function selectProjectAction(): Action
    {
        return Action::make('Select Projects')
            ->requiresConfirmation()
            ->action(function () {
                info('selected man');
                \Laravel\Prompts\info('alkdjlskdj');
                $path = Dialog::new()
                    ->folders()
                    ->open();

            });
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('livewire.laravel-projects');
    }
}
