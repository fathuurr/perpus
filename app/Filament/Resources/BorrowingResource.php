<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingResource\Pages;
use App\Filament\Resources\BorrowingResource\RelationManagers;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BorrowingResource extends Resource
{
    protected static ?string $model = Borrowing::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Student')
                    ->options(User::where('role', 'siswa')->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('book_id')
                    ->label('Book')
                    ->options(Book::where('stock', '>', 0)->pluck('title', 'id'))
                    ->required(),
                Forms\Components\DatePicker::make('borrowed_at')
                    ->required(),
                Forms\Components\DatePicker::make('returned_at'),
                Forms\Components\Select::make('status')
                    ->options([
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable(),
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book')
                    ->searchable(),
                Tables\Columns\TextColumn::make('borrowed_at')->date(),
                Tables\Columns\TextColumn::make('returned_at')->date(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'borrowed',
                        'success' => 'returned',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('return')
                    ->label('Return Book')
                    ->icon('heroicon-o-check')
                    ->action(function (Borrowing $record) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                        ]);

                        $user = $record->user;
                        $daysLate = Carbon::now()->diffInDays($record->borrowed_at->addDays(14), false);
                        if ($daysLate > 0) {
                            $fine = $daysLate * 1000; // 1000 per day
                            $user->decrement('balance', min($fine, $user->balance));
                        }
                    })
                    ->visible(function (Borrowing $record) {
                        $user = auth()->user();
                        return $record->status === 'borrowed' && in_array($user->role, ['super_admin', 'petugas_perpus']);
                    })
                ,
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function () {
                            $user = auth()->user();
                            return in_array($user->role, ['super_admin', 'petugas_perpus']);
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowings::route('/'),
            'create' => Pages\CreateBorrowing::route('/create'),
            'edit' => Pages\EditBorrowing::route('/{record}/edit'),
        ];
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return in_array($user->role, ['super_admin', 'admin']);
    }
}
