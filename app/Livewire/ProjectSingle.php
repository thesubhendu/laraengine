<?php

namespace App\Livewire;

use App\Blueprint\Services\CodeGenerator;
use App\Models\Project;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ProjectSingle extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;


    public  $cruds;
    public $project;

    public function mount($project)
    {
        $project = Project::find($project);
        $this->project = $project;
        $this->cruds = $project->cruds;

    }

    public function generateCode()
    {

        (new CodeGenerator($this->project))->generate();
        session()->flash('status', 'Code Generated');
        $this->redirectRoute('projects.show', $this->project->id);

    }

    public function goBack()
    {
        return $this->redirectRoute('projects.index');
    }
    public function addCrud($projectId)
    {
        return $this->redirectRoute('projects.crud', $projectId);
    }

    public function render()
    {
        return view('livewire.project-single');
    }
}
