<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Exception;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function afterCreate(): void
    {
        foreach ($this->record->detailProduk as $item){
            $produkPes = $item->produk;

            if($produkPes && $produkPes->Stok >= $item->qty){
               $produkPes->decrement('Stok', $item->qty);
            }else{
                throw new Exception("Stok Produk {$produkPes->Nama} tidak mencukupi");
            }
        }
    }
}
