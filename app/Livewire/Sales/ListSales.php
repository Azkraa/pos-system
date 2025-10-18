<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Illuminate\Contracts\View\View;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class ListSales extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query()->with(['customer','saleItems']))
            ->columns([
                TextColumn::make('customer.name')
                ->label('Nama pembeli')
                ->sortable(),
                TextColumn::make('saleItems.item.name')
                ->label('Produk terjual')
                ->bulleted()
                ->limitList(2)
                ->expandableLimitedList(),
                TextColumn::make('total')
                ->label('Total')
                ->money('IDR')
                ->sortable(),
                TextColumn::make('discount')
                ->label('Diskon')
                ->money('IDR'),
                TextColumn::make('paid_amount')
                ->label('Total pembayaran')
                ->money('IDR'),
                TextColumn::make('paymentMethod.name')
                ->label('Pembayaran'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('delete')
                ->requiresConfirmation()
                ->color('danger')
                ->action(fn (Sale $record) => $record->delete())
                ->successNotification(
                     Notification::make()
                        ->title('Hapus data penjualan berhasil')
                        ->success()
                )
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('deleteSelected')
                        ->label('Delete Selected')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->delete();

                            Notification::make()
                                ->title('Hapus data penjualan berhasil')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.sales.list-sales');
    }
}
