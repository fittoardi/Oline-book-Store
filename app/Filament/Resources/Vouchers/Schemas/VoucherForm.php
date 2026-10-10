<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Voucher')
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('code', Str::upper($state)) : null)
                            ->placeholder('KEMBANG10'),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('type')
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed amount (Rp)',
                            ])
                            ->required()
                            ->live(),
                        TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->suffix(fn (callable $get) => $get('type') === 'percentage' ? '%' : 'Rp'),
                        TextInput::make('minimum_purchase')
                            ->label('Minimum purchase')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->default(0),
                        TextInput::make('maximum_discount')
                            ->label('Maximum discount')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->visible(fn (callable $get) => $get('type') === 'percentage')
                            ->helperText('Kosongkan jika tanpa batas'),
                    ]),

                Section::make('Usage limits')
                    ->columns(3)
                    ->schema([
                        TextInput::make('usage_limit')
                            ->label('Total usage limit')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Kosongkan = tanpa batas'),
                        TextInput::make('usage_limit_per_user')
                            ->label('Per user limit')
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('used_count')
                            ->label('Used count')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->disabled(),
                    ]),

                Section::make('Schedule')
                    ->columns(3)
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Starts at'),
                        DateTimePicker::make('expires_at')
                            ->label('Expires at'),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }
}
