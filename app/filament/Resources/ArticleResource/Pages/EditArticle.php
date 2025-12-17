<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification; 

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

     // error handling pada tombol kategori (edit)
    protected function beforeSave(){
        if (! $this->data['category_id']) {
        Notification::make()
            ->title('Kategori belum di pilih')
            ->body('silahkan pilih kategori, klo belum ada buat dlu deh')
            ->danger()
            ->send();
        
        $this->halt();
        
        }
    }
}
