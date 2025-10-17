<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
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

class CreateCustomer extends Component implements HasActions, HasSchemas
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
                Section::make('Add the Inventory')
                ->description('Add the Inventory details as you want')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Customer Name')
                    ->required(),
                TextInput::make('email')
                    ->label('Customer Email')
                    ->unique()
                    ->required(),
                TextInput::make('phone')
                    ->label('Customer Phone Number')
                    ->unique()
                    ->numeric()
                    ->required(),
                ])
            ])
            ->statePath('data')
            ->model(Customer::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Customer::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
        ->title('Customer data created!')
        ->success()
        ->body("Customer data created successfully!")
        ->actions([
            Action::make('View Customer Table')
            ->button()
            ->url(route('customers.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.customer.create-customer');
    }
}
