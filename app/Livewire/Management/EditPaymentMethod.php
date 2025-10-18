<?php

namespace App\Livewire\Management;

use Livewire\Component;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\PaymentMethod;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EditPaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public PaymentMethod $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Metode Pembayaran')
                ->description('Perbarui metode pembayaran sesuai yang kamu inginkan')
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('Nama'),
                    Textarea::make('description')->label('Deskripsi'),
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
        ->title('Metode pembayaran diperbarui!')
        ->success()
        ->body("Metode pembayaran {$this->record->name} berhasil diperbarui!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
            ->button()
            ->url(route('payment.method.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-payment-method');
    }
}
