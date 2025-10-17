<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Illuminate\Support\Str;
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

class CreateItem extends Component implements HasActions, HasSchemas
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
                Section::make('Add the Item')
                ->description('fill the form to add new item')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Item Name')
                    ->required(),
                    TextInput::make('sku')
                    ->required()
                    ->unique()
                    ->readOnly()
                    ->suffixAction(
                        Action::make('generateSku')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->tooltip('Generate SKU')
                            ->action(function (callable $set) {
                            $sku = 'SKU-' . strtoupper(Str::random(8));
                            $set('sku', $sku);
                        })
                    ),
                    TextInput::make('price')
                    ->prefix('IDR')
                    ->required()
                    ->numeric(),
                    ToggleButtons::make('status')
                    ->label('Is this Item Active?')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive'
                    ])
                    ->default('active')
                    ->grouped()
                ])
            ])
            ->statePath('data')
            ->model(Item::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Item::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
        ->title('Item created!')
        ->success()
        ->body("Item created successfully!")
        ->actions([
            Action::make('View Items Table')
            ->button()
            ->url(route('items.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.items.create-item');
    }
}
