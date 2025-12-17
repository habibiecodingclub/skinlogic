<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
    
    // error handling pada tombol kategori (create)
    protected function beforeCreate(){
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
