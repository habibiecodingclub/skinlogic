<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerawatanResource\Pages;
use App\Filament\Resources\PerawatanResource\RelationManagers;
use App\Models\Perawatan;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
// use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
// use pxlrbt\FilamentExcel\Actions\Pages\ExportAction as PagesExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction ;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PerawatanResource extends Resource
{
    protected static ?string $model = Perawatan::class;

    protected static ?string $navigationIcon = 'heroicon-c-sparkles';

    protected static ?string $pluralModelLabel = "Perawatan";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make("Nama_Perawatan"),
                TextInput::make("Harga")
                ,

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make("Nama_Perawatan"),
                TextColumn::make("Harga")->state(function ($record){
                    $format = number_format($record->Harga, 0, ".", ",");
                    return "Rp. " . (string) $format;
                })
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()->fromTable()
                ])->label('download')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // DeleteAction::make()
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPerawatans::route('/'),
            'create' => Pages\CreatePerawatan::route('/create'),
            'edit' => Pages\EditPerawatan::route('/{record}/edit'),
        ];
    }
}
