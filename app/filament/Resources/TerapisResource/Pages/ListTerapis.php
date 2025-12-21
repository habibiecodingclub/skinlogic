<?php

namespace App\Filament\Resources\TerapisResource\Pages;

use App\Filament\Resources\TerapisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerapis extends ListRecords
{
    protected static string $resource = TerapisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
