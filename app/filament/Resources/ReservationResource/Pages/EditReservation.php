<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load perawatan items for form
        $record = $this->getRecord();
        $data['perawatan_items'] = $record->perawatans->map(function ($perawatan) {
            return [
                'perawatan_id' => $perawatan->id,
                'qty' => $perawatan->pivot->qty,
                'harga' => $perawatan->pivot->harga,
            ];
        })->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extract perawatan items
        $perawatanItems = $data['perawatan_items'] ?? [];
        unset($data['perawatan_items']);

        // Update reservation
        $record->update($data);

        // Update perawatans
        if (!empty($perawatanItems)) {
            /** @var Reservation $record */
            $record->savePerawatans($perawatanItems);
        }

        return $record;
    }
}
