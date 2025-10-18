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
                Section::make('Tambahkan Produk')
                ->description('Isi form dibawah ini untuk menambahkan produk')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Nama produk')
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
                    ->label('harga')
                    ->prefix('IDR')
                    ->required()
                    ->numeric(),
                    ToggleButtons::make('status')
                    ->label('Produk tersebut aktif?')
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
        ->title('Produk Ditambahkan')
        ->success()
        ->body("Berhasil menambahkan produk!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
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
