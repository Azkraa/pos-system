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
                Section::make('Edit Data Pembeli')
                ->description('Perbarui data pembeli sesuai yang kamu inginkan')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Nama pembeli'),
                    TextInput::make('email')
                    ->label('Email')
                    ->unique(),
                    TextInput::make('phone')
                    ->label('No. Hp')
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
        ->title('Data pembeli diperbarui!')
        ->success()
        ->body("Data pembeli {$this->record->name} berhasil diperbarui!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
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
