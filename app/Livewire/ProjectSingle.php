<?php

namespace App\Livewire;

use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component;
use Native\Laravel\Dialog;

class ProjectSingle extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public  $project;

    public function mount($project)
    {
        $this->project = Project::find($project);

    }

    public function create(): void
    {
        //save to db
        dd($this->form->getState());
    }

    public function goBack()
    {
        return $this->redirectRoute('projects');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Model')
                    ->columns(2)
                    ->schema([
                        $this->projectField(),
                        $this->modelNameField(),
                        $this->relationsBuilderRepeater(),
                    ]),

                Section::make('Define Table')
                    ->columns(1)
                    ->schema([
                        $this->tableColumnsBuilder(),
                    ]),
                Section::make('Controller')
                    ->statePath('controllers')
                    ->schema([
                        $this->controllerField(),
                    ])

            ])->columns(1)
            ->statePath('data');
    }


    private function tableColumnsBuilder()
    {
        return  Repeater::make('blueprint')
            ->label('Define Columns')
            ->schema([
                Grid::make(3)->schema([
                    TextInput::make('name')
                        ->required(),
                    Select::make('type')
                        ->options(config('blueprintgui.columnTypes'))
                        ->searchable()
                        ->required(),
                    TextInput::make('default'),
                ]),
                Grid::make(4)->schema([
                    Checkbox::make('nullable'),
                    Checkbox::make('unique'),
                    Checkbox::make('index'),
                    Checkbox::make('foreign'),
                ]),

            ])
            ->addActionLabel('Add Column');
    }

    /**
     * @return TextInput
     */
    private function modelNameField(): TextInput
    {
        return TextInput::make('name')
            ->label('Model Name')
            ->helperText('Must be singular, first letter uppercase and no special character')
            ->required()
            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule){
                $rule->where('project_id', $this->project->id);
            })
            ->regex('/^[A-Z][a-zA-Z]*$/')
            ->maxLength(15);
    }

    /**
     * @return Select
     */
    private function projectField(): Select
    {
        return Select::make('project_id')
            ->relationship('project', 'name')
            ->required()
            ->hidden()
            ->default($this->project->id);
    }

    /**
     * @return Radio
     */
    private function controllerField(): Radio
    {
        return Radio::make('type')
            ->options([
                'all' => 'Web',
                'api' => 'api',
            ])
            ->helperText('Choose type of controller if you want to generate controller Or Leave empty');
    }

    /**
     * @return Repeater
     */
    public function relationsBuilderRepeater(): Repeater
    {
        return Repeater::make('relations')
            ->label('Relations')
            ->helperText('BelongsTo relationship will be automatically added if you define foreign key with column type id')
            ->schema([
                Select::make('type')
                    ->options([
                        'hasMany' => 'hasMany',
                        'belongsToMany' => 'belongsToMany'
                    ]),
                Select::make('model')
                    ->options($this->project->cruds->pluck('name', 'name'))
            ])
            ->addActionLabel('Add Relationship');
    }


    public function render()
    {
        return view('livewire.project-single');
    }
}
