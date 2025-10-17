<?php

namespace App\Livewire\Items;

use Livewire\Component;
use App\Models\Inventory;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditInventory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Inventory $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit the Inventory')
                ->description('update the Inventory details as you want')
                ->columns(2)
                ->schema([
                    TextInput::make('item.name')
                    ->label('Item Name'),
                    TextInput::make('quantity')
                    ->integer(),
                ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
        ->title('Inventory updated!')
        ->success()
        ->body("Inventory {$this->record->name} has been updated successfully!")
        ->actions([
            Action::make('View Inventory Table')
            ->button()
            ->url(route('inventories.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-inventory');
    }
}
