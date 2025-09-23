<?php

namespace App\Filament\Resources\PerawatanResource\Pages;

use App\Filament\Resources\PerawatanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Collection;

class CreatePerawatan extends CreateRecord
{
    protected static string $resource = PerawatanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove produk_digunakan from the data to prevent it from being saved to the perawatans table
        unset($data['produk_digunakan']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();

        /** @var Collection $produkData */
        $produkData = collect($data['produk_digunakan'] ?? []);

        $syncData = $produkData->mapWithKeys(function (array $item) {
            return [
                $item['produk_id'] => [
                    'qty_digunakan' => $item['qty_digunakan'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]
            ];
        })->toArray();

        $this->record->produk()->attach($syncData);
    }
}
