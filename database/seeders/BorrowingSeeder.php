<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'siswa')->get();
        $books = Book::all();

        foreach ($students as $student) {
            // Each student borrows 1-3 books
            $numberOfBooks = rand(1, 3);
            $borrowedBooks = $books->random($numberOfBooks);

            foreach ($borrowedBooks as $book) {
                $borrowedAt = Carbon::now()->subDays(rand(1, 30));
                $status = rand(0, 1) ? 'borrowed' : 'returned';

                Borrowing::create([
                    'user_id' => $student->id,
                    'book_id' => $book->id,
                    'borrowed_at' => $borrowedAt,
                    'returned_at' => $status === 'returned' ? $borrowedAt->addDays(rand(1, 14)) : null,
                    'status' => $status,
                ]);

                // Update book's borrowed count
                $book->borrowed += ($status === 'borrowed' ? 1 : 0);
                $book->save();
            }
        }
    }
}
