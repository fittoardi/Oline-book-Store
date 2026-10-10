<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->copyable()
                    ->weight('medium'),
                TextColumn::make('name')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'percentage' ? 'Percent' : 'Fixed'),
                TextColumn::make('value')
                    ->formatStateUsing(fn ($record): string => $record->type === 'percentage'
                        ? rtrim(rtrim(number_format($record->value, 2, ',', '.'), '0'), ',').'%'
                        : 'Rp'.number_format($record->value, 0, ',', '.'))
                    ->label('Discount'),
                TextColumn::make('minimum_purchase')
                    ->label('Min. purchase')
                    ->money('IDR', locale: 'id')
                    ->toggleable(),
                TextColumn::make('used_count')
                    ->label('Used')
                    ->numeric()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('usage_limit')
                    ->label('Limit')
                    ->badge()
                    ->color('gray')
                    ->placeholder('∞'),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : null),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active'),
                TernaryFilter::make('expired')
                    ->label('Expired')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('expires_at')->where('expires_at', '<', now()),
                        false: fn (Builder $query) => $query->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now())),
                        blank: fn (Builder $query) => $query,
                    ),
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
