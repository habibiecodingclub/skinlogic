<?php

namespace App\Filament\Resources\StokMovementResource\Pages;

use App\Filament\Resources\StokMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStokMovement extends CreateRecord
{
    protected static string $resource = StokMovementResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $produk = \App\Models\Produk::find($data['produk_id']);

            if (!$produk) {
                throw new \Exception('Produk tidak ditemukan');
            }

            // **GUNAKAN METHOD tambahStok() atau kurangiStok()**
            if ($data['tipe'] === 'masuk') {
                $movement = $produk->tambahStok($data['jumlah'], $data['keterangan'], $data['tanggal']);
            } else {
                $movement = $produk->kurangiStok($data['jumlah'], $data['keterangan'], $data['tanggal']);
            }

            return $movement;
        });
    }
}
