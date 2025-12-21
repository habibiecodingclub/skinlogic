<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TerapisResource\Pages;
use App\Models\Terapis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class TerapisResource extends Resource
{
    protected static ?string $model = Terapis::class;

    // Ganti ikon agar lebih sesuai (User Group)
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    // Label Menu di Sidebar
    protected static ?string $navigationLabel = 'Data Terapis';
    protected static ?string $modelLabel = 'Terapis';
    protected static ?string $navigationGroup = 'Manajemen Klinik'; // Opsional: Biar rapi dalam grup
    protected static ?int $navigationSort = 2; // Urutan menu

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil Terapis')
                    ->description('Masukkan data lengkap terapis di sini.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Input Nama
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Dr. Sarah Wijaya'),

                                // Input Spesialisasi
                                Forms\Components\TextInput::make('spesialisasi')
                                    ->label('Spesialisasi / Jabatan')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Dokter Estetika / Beautician'),
                            ]),

                        // Input Foto
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Profil')
                            ->image() // Validasi harus gambar
                            ->avatar() // Tampilan bulat saat upload
                            ->imageEditor() // Fitur crop/edit ringan
                            ->directory('terapis') // Simpan di storage/app/public/terapis
                            ->columnSpanFull(),

                        // Toggle Status Aktif
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Jika non-aktif, terapis tidak akan muncul di form reservasi.')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Foto
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular() // Tampilan bulat
                    ->defaultImageUrl(url('/images/default-avatar.png')), // Gambar default jika kosong (opsional)

                // Kolom Nama
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Terapis')
                    ->searchable() // Bisa dicari
                    ->sortable()
                    ->weight('bold'),

                // Kolom Spesialisasi
                Tables\Columns\TextColumn::make('spesialisasi')
                    ->label('Spesialisasi')
                    ->searchable(),

                // Kolom Status
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean() // Menampilkan Centang/Silang
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                // Kolom Tanggal Dibuat
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter Terapis Aktif/Non-Aktif
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
            'index' => Pages\ListTerapis::route('/'),
            'create' => Pages\CreateTerapis::route('/create'),
            'edit' => Pages\EditTerapis::route('/{record}/edit'),
        ];
    }
}