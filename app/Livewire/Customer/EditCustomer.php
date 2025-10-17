<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
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

class EditCustomer extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Customer $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit the Customer')
                ->description('update the customer details as you want')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Customer Name'),
                    TextInput::make('email')
                    ->unique(),
                    TextInput::make('phone')
                    ->tel(),
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
        ->title('Customer updated!')
        ->success()
        ->body("Customer {$this->record->name} has been updated successfully!")
        ->actions([
            Action::make('View Customers Table')
            ->button()
            ->url(route('customers.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.customer.edit-customer');
    }
}
