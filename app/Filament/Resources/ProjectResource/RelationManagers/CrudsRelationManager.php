<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CrudsRelationManager extends RelationManager
{
    protected static string $relationship = 'cruds';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                ->relationship('project', 'name')
                ->required()
                ->hidden()
                ->default($this->getOwnerRecord()->id)
                ,
                Forms\Components\TextInput::make('name')
                    ->label('Model Name')
                    ->helperText('Must be singular')
                ->required()
                ->maxLength(255),
                Repeater::make('blueprint')
                    ->label('Define Columns')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        Select::make('type')
                            ->options(config('blueprintgui.columnTypes'))
                            ->searchable()
                            ->required(),
                        Checkbox::make('nullable'),
                        Checkbox::make('unique'),
                        Checkbox::make('index'),
                        Checkbox::make('foreign'),
                        TextInput::make('default'),
                        //todo add index, length and other modifiers
                    ])->columns(2)
            ]);
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
                Tables\Actions\CreateAction::make(),
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
}
