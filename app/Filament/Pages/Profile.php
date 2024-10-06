<?php

namespace App\Filament\Pages;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.pages.profile';

    public $name;
    public $email;
    public $balance;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->balance = $user->balance;

        $this->form->fill([
            'name' => Auth::user()->name,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->disabled(),
            Forms\Components\TextInput::make('balance')
                ->disabled()
                ->visible(fn () => Auth::user()->role === 'siswa'),
        ];
    }


    public function updateName()
    {
        $data = $this->form->getState();

        Auth::user()->update([
            'name' => $data['name'],
        ]);

        Notification::make()
            ->title('Profil diperbarui')
            ->success()
            ->send();
    }

    public function getBorrowingHistoryProperty()
    {
        return Auth::user()->borrowings()->with('book')->latest('borrowed_at')->get();
    }
}
