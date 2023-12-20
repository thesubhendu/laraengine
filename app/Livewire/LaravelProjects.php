<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Native\Laravel\Dialog;


class LaravelProjects extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public $projects;

    public function mount()
    {
        $this->projects = Project::all();
    }

    public function selectProjectAction()
    {
        $path = Dialog::new()
            ->folders()
            ->open();

        if($path){
            $project = Project::where('path', $path)->first();
            if(!$project){
                $project = new Project();
                $project->path = $path;
                $project->name = $path;
                $project->save();
            }
            return $this->redirectRoute('projects.show', $project->id);
        }
    }

    public function visitProject($projectId)
    {
//        return $this->redirect()->route
        return $this->redirectRoute('projects.show', ['project' => $projectId]);
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
