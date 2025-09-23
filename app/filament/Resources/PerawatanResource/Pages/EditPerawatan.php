<?php

namespace App\Filament\Resources\PerawatanResource\Pages;

use App\Filament\Resources\PerawatanResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Collection;

class EditPerawatan extends EditRecord
{
    protected static string $resource = PerawatanResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove produk_digunakan from the data to prevent it from being saved to the perawatans table
        unset($data['produk_digunakan']);
        return $data;
    }

    protected function afterSave(): void
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

        $this->record->produk()->sync($syncData);
    }
}
