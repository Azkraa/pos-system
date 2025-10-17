<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\ToggleButtons;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Item $record;

    public ?array $data = [];

    public function mount(): void
    {
        // populate the default values from db
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit the Item')
                ->description('update the item details as you want')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Item Name'),
                    TextInput::make('sku')
                    ->unique(),
                    TextInput::make('price')
                    ->prefix('IDR')
                    ->numeric(),
                    ToggleButtons::make('status')
                    ->label('Is this Item Active?')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive'
                    ])
                    ->grouped()
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
        ->title('Item updated!')
        ->success()
        ->body("Item {$this->record->name} has been updated successfully!")
        ->actions([
            Action::make('View Items')
            ->button()
            ->url(route('items.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-item');
    }
}
