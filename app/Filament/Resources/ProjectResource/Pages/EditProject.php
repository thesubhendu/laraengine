<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Blueprint\Services\CodeGenerator;
use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Download Project')
            ->action(fn (Project $record) => (new CodeGenerator($record))->generate())
            ,
        ];
    }
}
