<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $pluralModelLabel = "Pelanggan";

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("Nama")
                    ->required()
                    ->placeholder("Nama Pelanggan")
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->validationMessages([
                        'regex' => 'Nama hanya boleh berisi huruf dan spasi.',
                    ]),

                TextInput::make("Pekerjaan")
                    ->required()
                    ->placeholder("Guru")
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->validationMessages([
                        'regex' => 'Pekerjaan hanya boleh berisi huruf dan spasi.',
                    ]),

                TextInput::make('Nomor_Telepon')
                    ->tel()
                    ->required()
                    ->placeholder("085312984232")
                    ->telRegex('/^[+]?[0-9]{10,15}$/')
                    ->validationMessages([
                        "regex" => "Masukkan nomor telepon dengan benar! (cth: 085****)",
                    ]),

                DatePicker::make("Tanggal_Lahir")
                    ->maxDate(now())
                    ->required(),

                TextInput::make("Email")
                    ->email()
                    ->required()
                    ->placeholder("pelanggan@gmail.com"),

                Select::make("Status")
                    ->options([
                        'Member' => "Member",
                        'Non Member' => "Non Member",
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("Nama")->searchable(),
                TextColumn::make("Pekerjaan"),
                TextColumn::make("Nomor_Telepon"),
                TextColumn::make("Tanggal_Lahir"),
                TextColumn::make("Email"),
                TextColumn::make("Status"),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // ExportAction::make()->exports([
                //     ExcelExport::make()->fromTable()
                // ])->label('Download')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
