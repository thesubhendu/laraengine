<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class CrudsRelationManager extends RelationManager
{
    protected static string $relationship = 'cruds';
    protected static ?string $title = 'Model';

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

            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
//            Tables\Columns\TextColumn::make('project.name')
//                ->numeric()
//                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->label('Tables')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Model'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    private function tableColumnsBuilder(): Forms\Components\Field
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
                //todo add index, length and other modifiers
            ])
            ->addActionLabel('Add Column');
    }

    /**
     * @return TextInput
     */
    private function modelNameField(): TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->label('Model Name')
            ->helperText('Must be singular, first letter uppercase and no special character')
            ->required()
            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule){
                $rule->where('project_id', $this->getOwnerRecord()->id);
            })
            ->regex('/^[A-Z][a-zA-Z]*$/')
            ->maxLength(15);
    }

    /**
     * @return Select
     */
    private function projectField(): Select
    {
        return Forms\Components\Select::make('project_id')
            ->relationship('project', 'name')
            ->required()
            ->hidden()
            ->default($this->getOwnerRecord()->id);
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
                    ->options($this->getOwnerRecord()->cruds->pluck('name', 'name'))
            ])
            ->addActionLabel('Add Relationship');
    }

}
