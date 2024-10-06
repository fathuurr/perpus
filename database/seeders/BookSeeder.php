<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'stock' => 5],
            ['title' => '1984', 'author' => 'George Orwell', 'stock' => 3],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'stock' => 4],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'stock' => 2],
            ['title' => 'Moby Dick', 'author' => 'Herman Melville', 'stock' => 3],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
