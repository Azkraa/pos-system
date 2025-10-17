<?php

namespace App\Livewire;

use App\Models\Sale;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LatestSales extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query()->with(['customer','saleItems']))
            ->columns([
                TextColumn::make('customer.name')
                ->sortable(),
                TextColumn::make('saleItems.item.name')
                ->label('Sold Items')
                ->bulleted()
                ->limitList(2)
                ->expandableLimitedList(),
                TextColumn::make('total')
                ->money('IDR')
                ->sortable(),
                TextColumn::make('discount')
                ->money('IDR'),
                TextColumn::make('paid_amount')
                ->money('IDR'),
                TextColumn::make('paymentMethod.name'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('View Receipt')
                ->icon('heroicon-o-document-text')
                ->label('Print Receipt')
                ->url(fn ($record) => route('receipt.view', $record->id))
                ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
