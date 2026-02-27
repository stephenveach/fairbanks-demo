<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('collection')
                    ->default('products')
                    ->dehydrated(),
                Hidden::make('blueprint')
                    ->default('product')
                    ->dehydrated(),
                Hidden::make('site')
                    ->default('default')
                    ->dehydrated(),
                Section::make('Core Information')
                    ->schema([
                        TextInput::make('data.title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Used as the product URL segment (for example: /products/your-slug).')
                            ->rules([
                                Rule::unique('entries', 'slug')
                                    ->where(fn ($query) => $query->where('collection', 'products'))
                                    ->ignore(request()->route('record')),
                            ]),
                        TextInput::make('data.model_number')
                            ->label('Model Number')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('data.price')
                            ->label('Price (USD)')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->step(0.01),
                        TextInput::make('data.product_family')
                            ->label('Product Family')
                            ->maxLength(255),
                        Select::make('data.status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'discontinued' => 'Discontinued',
                                'coming_soon' => 'Coming soon',
                            ])
                            ->default('active')
                            ->required(),
                        Select::make('published')
                            ->options([
                                true => 'Published',
                                false => 'Draft',
                            ])
                            ->required()
                            ->default(true),
                    ])
                    ->columns(2),
                Section::make('Content')
                    ->schema([
                        Textarea::make('data.short_description')
                            ->label('Short Description')
                            ->rows(3)
                            ->maxLength(1000),
                        Textarea::make('data.operating_conditions')
                            ->label('Operating Conditions')
                            ->rows(3)
                            ->maxLength(2000),
                    ])
                    ->columns(1),
            ]);
    }
}
