<?php

namespace App\Livewire\Management;

use App\Models\User;
use Livewire\Component;
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

class EditUser extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit the User')
                ->description('update the user details as you want')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                    ->label('Nama user'),
                    TextInput::make('email')
                    ->label('Email')
                    ->unique(),
                    Select::make('role')
                    ->label('Role')
                    ->options([
                        'cashier' => 'Cashier',
                        'admin' => 'Admin',
                    ])
                    ->default(fn ($record) => $record?->role),
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
        ->title('User diperbarui!')
        ->success()
        ->body("User {$this->record->name} berhasil diperbarui!")
        ->actions([
            Action::make('Kembali ke halaman sebelumnya')
            ->button()
            ->url(route('users.index')),
        ])
        ->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-user');
    }
}
