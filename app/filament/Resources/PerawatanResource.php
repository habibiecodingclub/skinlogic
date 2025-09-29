<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerawatanResource\Pages;
use App\Models\Perawatan;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rule;

class PerawatanResource extends Resource
{
    protected static ?string $model = Perawatan::class;

    protected static ?string $navigationIcon = 'heroicon-c-sparkles';

    protected static ?string $pluralModelLabel = "Perawatan";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Informasi Dasar Perawatan
                Forms\Components\Section::make('Informasi Perawatan')
                    ->schema([
                        TextInput::make("Nama_Perawatan")
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        TextInput::make("Harga")
                            ->label('Harga')
                            ->required()
                            ->prefix('Rp')
                            ->maxLength(20)
                            ->formatStateUsing(fn ($state) => $state !== null ? number_format((int) $state, 0, ',', '.') : '')
                            ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', $state))
                            ->rule(function () {
                                return function ($attribute, $value, $fail) {
                                    // Custom validation untuk menerima format dengan titik
                                    $numericValue = preg_replace('/[^\d]/', '', $value);
                                    if (!is_numeric($numericValue) || $numericValue < 0) {
                                        $fail('Harga harus berupa angka yang valid.');
                                    }
                                    if ($numericValue > 999999999999) {
                                        $fail('Harga terlalu besar.');
                                    }
                                };
                            })
                            ->validationMessages([
                                'required' => 'Harga harus diisi',
                            ])
                            ->helperText('Masukkan harga (contoh: 1000000 atau 1.000.000)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!empty($state)) {
                                    $numericValue = (int) preg_replace('/[^\d]/', '', $state);
                                    $set('Harga', $numericValue > 0 ? number_format($numericValue, 0, ',', '.') : '');
                                }
                            })
                            ->placeholder('0')
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                // Produk yang Digunakan
                Forms\Components\Section::make('Produk yang Digunakan dalam Perawatan')
                    ->schema([
                        Repeater::make('produk_digunakan')
                            ->label('')
                            ->schema([
                                Select::make('produk_id')
                                    ->label('Pilih Produk')
                                    ->options(Produk::all()->pluck('Nama', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $produk = Produk::find($state);
                                            if ($produk) {
                                                $set('nama_produk', $produk->Nama);
                                                $set('stok_tersedia', $produk->Stok);
                                                $set('harga_satuan', floatval($produk->Harga));
                                                $set('qty_digunakan', 1); // Default qty
                                                $set('subtotal', floatval($produk->Harga) * 1);
                                            }
                                        } else {
                                            $set('nama_produk', null);
                                            $set('stok_tersedia', 0);
                                            $set('harga_satuan', 0);
                                            $set('qty_digunakan', 1);
                                            $set('subtotal', 0);
                                        }
                                    })
                                    ->columnSpan(2),

                                TextInput::make('nama_produk')
                                    ->label('Nama Produk')
                                    ->readOnly()
                                    ->dehydrated(false)
                                    ->columnSpan(1),

                                TextInput::make('stok_tersedia')
                                    ->label('Stok Tersedia')
                                    ->numeric()
                                    ->readOnly()
                                    ->dehydrated(false)
                                    ->columnSpan(1),

                                TextInput::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(false)
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                                    ->columnSpan(1),

                                TextInput::make('qty_digunakan')
                                    ->label('Jumlah Digunakan')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $harga = floatval($get('harga_satuan') ?? 0);
                                        $qty = floatval($state ?? 1);
                                        $set('subtotal', $harga * $qty);
                                    })
                                    ->columnSpan(1),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->dehydrated(false)
                                    ->formatStateUsing(fn ($state) => is_numeric($state) ? number_format($state, 0, ',', '.') : $state)
                                    ->columnSpan(1),

                                TextInput::make('keterangan')
                                    ->label('Keterangan Penggunaan')
                                    ->maxLength(500)
                                    ->placeholder('Cara penggunaan, area yang dirawat, dll.')
                                    ->columnSpan(3),
                            ])
                            ->columns(3)
                            ->addActionLabel('Tambah Produk')
                            ->defaultItems(0)
                            ->afterStateHydrated(function (Repeater $component, $context, $livewire) {
                                if ($context === 'edit') {
                                    $record = $livewire->getRecord();
                                    if ($record) {
                                        $items = $record->produk->map(function ($produk) {
                                            return [
                                                'produk_id' => $produk->id,
                                                'nama_produk' => $produk->Nama,
                                                'stok_tersedia' => $produk->Stok,
                                                'harga_satuan' => floatval($produk->Harga),
                                                'qty_digunakan' => $produk->pivot->qty_digunakan,
                                                'subtotal' => floatval($produk->Harga) * $produk->pivot->qty_digunakan,
                                                'keterangan' => $produk->pivot->keterangan,
                                            ];
                                        })->toArray();
                                        $component->state($items);
                                    }
                                }
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("Nama_Perawatan")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("Harga")
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('lihat_produk')
                    ->label('Produk')
                    ->icon('heroicon-o-cube')
                    ->modalHeading('Produk yang Digunakan')
                    ->modalContent(function (Perawatan $record) {
                        $html = '<div class="space-y-3">';

                        if ($record->produk->count() > 0) {
                            foreach ($record->produk as $produk) {
                                $subtotal = $produk->Harga * $produk->pivot->qty_digunakan;
                                $html .= '
                                    <div class="border rounded-lg p-3">
                                        <div class="font-semibold">' . $produk->Nama . '</div>
                                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mt-1">
                                            <div>Jumlah: ' . $produk->pivot->qty_digunakan . ' unit</div>
                                            <div>Harga: Rp ' . number_format($produk->Harga, 0, ',', '.') . '</div>
                                            <div>Subtotal: Rp ' . number_format($subtotal, 0, ',', '.') . '</div>
                                            <div>Keterangan: ' . ($produk->pivot->keterangan ?? '-') . '</div>
                                        </div>
                                    </div>
                                ';
                            }
                        } else {
                            $html .= '<p class="text-gray-500">Tidak ada produk yang digunakan.</p>';
                        }

                        $html .= '</div>';
                        return new HtmlString($html);
                    })
                    ->modalSubmitAction(false),
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

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->user()->hasRole('kasir') || auth()->user()->hasAnyRole(['admin', 'manajer']);
    // }
}
