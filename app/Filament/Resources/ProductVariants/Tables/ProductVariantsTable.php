<?php

namespace App\Filament\Resources\ProductVariants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Book')
                    ->searchable()
                    ->limit(30)
                    ->sortable(),
                TextColumn::make('format')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                TextColumn::make('sku')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('price')
                    ->money('IDR', locale: 'id')
                    ->placeholder('Product price'),
                TextColumn::make('stock')
                    ->numeric()
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : ($state < 10 ? 'warning' : 'success'))
                    ->sortable(),
                TextColumn::make('available_stock')
                    ->label('Available')
                    ->state(fn ($record) => $record->availableStock())
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'success'),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('product_id')
            ->filters([
                SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('format')
                    ->options([
                        'paperback' => 'Paperback',
                        'hardcover' => 'Hardcover',
                        'gift_edition' => 'Gift edition',
                    ]),
                TernaryFilter::make('is_active'),
                Filter::make('out_of_stock')
                    ->label('Out of stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock', '<=', 0)),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
