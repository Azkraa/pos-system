<?php

namespace App\Livewire\Management;

use App\Models\User;
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
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\Select;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class CreateUser extends Component implements HasActions, HasSchemas
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
                Section::make('Tambahkan User')
                ->description('Tambahkan detail User baru sesuai yang kamu inginkan')
                ->columns(2)
                ->schema([
                TextInput::make('name')
                    ->label('Nama user')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->unique()
                    ->required(),
                Select::make('role')
                ->required()
                ->options([
                    'cashier' => 'Cashier',
                    'admin' => 'Admin',
                ])
                ->native(false),
                TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->unique()
                    ->readOnly()
                    ->password()
                    ->revealable()
                    ->suffixAction(
                        Action::make('generatePassword')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->tooltip('Generate Password')
                            ->action(function (callable $set) {
                            $password = strtoupper(Str::random(8));
                            $set('password', $password);
                        })
                    ),
                ])
            ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();

        Notification::make()
        ->title('User ditambahkan!')
        ->success()
        ->body("User berhasil ditambahkan!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
            ->button()
            ->url(route('users.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-user');
    }
}
