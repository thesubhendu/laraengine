<?php

namespace App\Filament\Resources\CrudResource\Pages;

use App\Filament\Resources\CrudResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCrud extends CreateRecord
{
    protected static string $resource = CrudResource::class;
}
