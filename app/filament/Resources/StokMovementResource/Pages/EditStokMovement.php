<?php

namespace App\Filament\Resources\StokMovementResource\Pages;

use App\Filament\Resources\StokMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStokMovement extends EditRecord
{
    protected static string $resource = StokMovementResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $originalData = $this->record->toArray();

        // Handle update stok movement (lebih kompleks, perlu adjust stok)
        // Untuk sementara, lebih baik nonaktifkan edit atau handle dengan hati-hati

        return $data;
    }
}
