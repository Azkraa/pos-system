<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateInventory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tambahkan Stok')
                ->description('Tambahkan stok pada produk baru')
                ->columns(2)
                ->schema([
                    Select::make('item_id')
                    ->relationship('item', 'name')
                    ->label('Produk')
                    ->searchable()
                    ->preload()
                    ->native(false),
                    TextInput::make('quantity')
                    ->label('Stok')
                    ->numeric(),
                ])
            ])
             ->statePath('data')
            ->model(Inventory::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Inventory::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
        ->title('Stok ditambahkan')
        ->success()
        ->body("Berhasil menambahkan stok baru!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
            ->button()
            ->url(route('inventories.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.items.create-inventory');
    }
}
