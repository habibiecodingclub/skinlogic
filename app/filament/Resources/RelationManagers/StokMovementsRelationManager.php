<?php

namespace App\Filament\Resources\ProdukResource\RelationManagers;

use App\Models\StokMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StokMovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'stokMovements';

    protected static ?string $title = 'Riwayat Stok';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipe')
                    ->options([
                        'masuk' => 'Stok Masuk',
                        'keluar' => 'Stok Keluar',
                    ])
                    ->required()
                    ->disabled(fn ($operation) => $operation === 'edit'), // Tidak bisa edit tipe setelah dibuat

                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('keterangan')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'masuk' => 'Stok Masuk',
                        'keluar' => 'Stok Keluar',
                    ]),

                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->before(function (Tables\Actions\CreateAction $action, RelationManager $livewire) {
                        // Validasi dan update stok otomatis saat create
                        $data = $action->getFormData();

                        if ($data['tipe'] === 'masuk') {
                            $livewire->ownerRecord->increment('Stok', $data['jumlah']);
                        } else {
                            if ($livewire->ownerRecord->Stok < $data['jumlah']) {
                                throw new \Exception('Stok tidak mencukupi');
                            }
                            $livewire->ownerRecord->decrement('Stok', $data['jumlah']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function (Tables\Actions\EditAction $action, StokMovement $record, RelationManager $livewire) {
                        // Handle edit dengan memperhitungkan perubahan stok
                        $data = $action->getFormData();
                        $oldJumlah = $record->jumlah;
                        $newJumlah = $data['jumlah'];

                        $difference = $newJumlah - $oldJumlah;

                        if ($record->tipe === 'masuk') {
                            $livewire->ownerRecord->increment('Stok', $difference);
                        } else {
                            if ($livewire->ownerRecord->Stok < $difference) {
                                throw new \Exception('Stok tidak mencukupi');
                            }
                            $livewire->ownerRecord->decrement('Stok', $difference);
                        }
                    }),

                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, StokMovement $record, RelationManager $livewire) {
                        // Kembalikan stok saat menghapus record
                        if ($record->tipe === 'masuk') {
                            $livewire->ownerRecord->decrement('Stok', $record->jumlah);
                        } else {
                            $livewire->ownerRecord->increment('Stok', $record->jumlah);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, $records, RelationManager $livewire) {
                            // Handle bulk delete dengan mengembalikan stok
                            foreach ($records as $record) {
                                if ($record->tipe === 'masuk') {
                                    $livewire->ownerRecord->decrement('Stok', $record->jumlah);
                                } else {
                                    $livewire->ownerRecord->increment('Stok', $record->jumlah);
                                }
                            }
                        }),
                ]),
            ]);
    }
}
