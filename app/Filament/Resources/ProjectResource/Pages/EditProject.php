<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Blueprint\Services\CodeGenerator;
use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Native\Laravel\Dialog;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Select Project')
                ->action(function (Project $record) {
                    $path = Dialog::new()
                        ->folders()
                        ->open();
                    $record->path= $path;
                    $record->save();

                     }),
            Actions\Action::make('Download Project')
                ->action(fn(Project $record) => (new CodeGenerator($record))->generate())
            ,
        ];
    }
}
