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
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreatePaymentMethod extends Component implements HasActions, HasSchemas
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
                Section::make('Tambahkan Metode Pembayaran')
                ->description('Tambahkan metode pembayaran sesuai yang kamu inginkan')
                ->columns(2)
                ->schema([
                TextInput::make('name')
                    ->label('Nama Metode Pembayaran')
                    ->required(),
                TextInput::make('description')
                    ->label('Deskripsi')
                    ->required(),
                ])
            ])
            ->statePath('data')
            ->model(PaymentMethod::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = PaymentMethod::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
        ->title('Metode pembayaran ditambahkan!')
        ->success()
        ->body("Metode pembayaran berhasil ditambahkan!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
            ->button()
            ->url(route('payment.method.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-payment-method');
    }
}
