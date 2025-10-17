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
                Section::make('Add the Payment Method')
                ->description('Add the payment method details as you want')
                ->columns(2)
                ->schema([
                TextInput::make('name')
                    ->label('Payment Name')
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
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
        ->title('Payment method created!')
        ->success()
        ->body("Payment method created successfully!")
        ->actions([
            Action::make('View Payment Method Table')
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
