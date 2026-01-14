<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();

        foreach($authors as $author) {
            Post::factory()
                ->count(3)
                ->create([
                    'author_id'=> $author->id
                ]);
        }
    }
}
