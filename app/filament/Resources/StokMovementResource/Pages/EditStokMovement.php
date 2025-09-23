<?php

namespace App\Filament\Resources\StokMovementResource\Pages;

use App\Filament\Resources\StokMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStokMovement extends EditRecord
{
    protected static string $resource = StokMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
