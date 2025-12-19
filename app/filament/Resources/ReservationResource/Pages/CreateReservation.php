<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Extract perawatan items
        $perawatanItems = $data['perawatan_items'] ?? [];
        unset($data['perawatan_items']);

        // Create reservation
        $reservation = Reservation::create($data);

        // Save perawatans
        if (!empty($perawatanItems)) {
            $reservation->savePerawatans($perawatanItems);
        }

        return $reservation;
    }
}
