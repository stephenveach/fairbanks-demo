<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data.title')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('data.model_number')
                    ->label('Model Number')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('data.product_family')
                    ->label('Family'),
                TextColumn::make('data.price')
                    ->label('Price')
                    ->money('USD'),
                TextColumn::make('data.status')
                    ->label('Status')
                    ->badge(),
                IconColumn::make('published')
                    ->boolean()
                    ->label('Published'),
                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'coming_soon' => 'Coming soon',
                        'discontinued' => 'Discontinued',
                    ])
                    ->query(function ($query, array $data) {
                        if (! filled($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->where('data->status', $data['value']);
                    }),
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
