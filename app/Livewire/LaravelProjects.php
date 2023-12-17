<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Native\Laravel\Dialog;

class LaravelProjects extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
//                Actions\Action::make('Select Project')
//                    ->action(function (Project $record) {
//                        $path = Dialog::new()
//                            ->folders()
//                            ->open();
//                        $record->path= $path;
//                        $record->save();
//
//                    }),
                TextInput::make('title')
                    ->required(),
                MarkdownEditor::make('content'),
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
