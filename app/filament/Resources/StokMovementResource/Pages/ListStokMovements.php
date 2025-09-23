<?php

namespace App\Filament\Resources\StokMovementResource\Pages;

use App\Filament\Resources\StokMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStokMovements extends ListRecords
{
    protected static string $resource = StokMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
