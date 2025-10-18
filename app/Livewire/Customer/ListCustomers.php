<?php

namespace App\Livewire\Customer;


use Livewire\Component;
use App\Models\Customer;
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

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Customer::query())
            ->columns([
                TextColumn::make('name')
                ->label('Nama pembeli')->searchable()->sortable(),
                TextColumn::make('email')
                ->label('Email')->searchable()->sortable(),
                TextColumn::make('phone')
                ->label('No. Hp')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                ->label('Tambah Pembeli')
                ->url(fn (): string => route('customers.create'))
            ])
            ->recordActions([
                Action::make('delete')
                ->requiresConfirmation()
                ->color('danger')
                ->action(fn (Customer $record) => $record->delete())
                ->successNotification(
                    Notification::make()
                    ->title('Berhasil menghapus data pembeli')
                    ->success()
                ),
                Action::make('edit')
                ->url(fn (Customer $record): string => route('customers.update', $record))
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
                                ->title('Berhasil menghapus data pembeli')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.customer.list-customers');
    }
}
