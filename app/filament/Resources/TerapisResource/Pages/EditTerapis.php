<?php

namespace App\Filament\Resources\TerapisResource\Pages;

use App\Filament\Resources\TerapisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTerapis extends EditRecord
{
    protected static string $resource = TerapisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
