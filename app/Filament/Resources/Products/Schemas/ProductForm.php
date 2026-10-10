<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Book information')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL: /shop/{slug}'),
                        TextInput::make('author')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('isbn')
                            ->label('ISBN')
                            ->maxLength(20),
                        TextInput::make('publisher')
                            ->maxLength(255),
                        TextInput::make('page_count')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('pages'),
                        TextInput::make('language')
                            ->required()
                            ->default('id')
                            ->maxLength(10),
                    ]),

                Section::make('Description')
                    ->schema([
                        Textarea::make('description')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pricing')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText('Harga dasar (paperback)'),
                        TextInput::make('compare_price')
                            ->label('Compare price (harga coret)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText('Kosongkan jika tidak ada diskon'),
                    ]),

                Section::make('Cover image')
                    ->schema([
                        FileUpload::make('cover')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Sampul utama buku. URL eksternal juga bisa dipakai jika upload tidak diinginkan.')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Visibility')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Tampil di katalog'),
                        Toggle::make('is_featured')
                            ->helperText('Tampil di bagian pilihan'),
                        Toggle::make('is_new')
                            ->helperText('Badge NEW'),
                    ]),
            ]);
    }
}
