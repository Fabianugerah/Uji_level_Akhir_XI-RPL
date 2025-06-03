<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'name' => 'Harry Potter and the Sorcerer\'s Stone',
                'description' => 'A young boy discovers he is a wizard and attends a magical school filled with wonder and danger',
                'author' => 'J.K. Rowling',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Fault in Our Stars',
                'description' => 'Two teenagers with cancer fall in love while grappling with life and mortality.',
                'author' => 'John Green',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Milea : Suara dari Dilan',
                'description' => 'This book is a continuation of the Dilan story, focusing on Milea\'s perspective.',
                'author' => 'Pidi Baiq',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Shining',
                'description' => 'A family isolated in a haunted hotel faces terrifying supernatural forces.',
                'author' => 'Stephen King',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bumi Manusia',
                'description' => 'This novel tells the story of Minke, a young Javanese student in colonial Indonesia, and his love for Annelies.',
                'author' => 'Pramoedya Ananta Toer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
