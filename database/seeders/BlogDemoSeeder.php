<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Seeder;

class BlogDemoSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'name' => 'Anna Tamm',
                'email' => 'anna.tamm@example.com',
                'bio' => 'Anna writes about role-playing games, story design, and memorable fantasy worlds.',
                'posts' => [
                    [
                        'title' => 'Why Story-Driven RPGs Stay With Us',
                        'text' => 'Story-driven RPGs remain memorable because they connect gameplay with strong characters, difficult choices, and long-term world building.',
                        'published' => true,
                    ],
                    [
                        'title' => 'Three Things I Look For in an Open World',
                        'text' => 'A great open world needs atmosphere, meaningful exploration, and enough small details to reward curiosity.',
                        'published' => true,
                    ],
                ],
            ],
            [
                'name' => 'Markus Saar',
                'email' => 'markus.saar@example.com',
                'bio' => 'Markus focuses on strategy games, competitive balance, and game systems that reward planning.',
                'posts' => [
                    [
                        'title' => 'Why Strategy Games Are So Satisfying',
                        'text' => 'Strategy games are satisfying because every decision matters and improvement comes from understanding systems more deeply over time.',
                        'published' => true,
                    ],
                    [
                        'title' => 'Turn-Based vs Real-Time: What Changes the Feeling?',
                        'text' => 'Turn-based strategy encourages deliberate planning, while real-time strategy adds pressure and rewards fast adaptation.',
                        'published' => false,
                    ],
                ],
            ],
            [
                'name' => 'Liisa Kask',
                'email' => 'liisa.kask@example.com',
                'bio' => 'Liisa enjoys indie games, creative mechanics, and games that experiment with visual style.',
                'posts' => [
                    [
                        'title' => 'What Makes Indie Games Feel Fresh',
                        'text' => 'Indie games often feel fresh because smaller teams can take creative risks and build around one strong idea.',
                        'published' => true,
                    ],
                    [
                        'title' => 'My Favorite Small Games to Recommend',
                        'text' => 'Shorter indie games can leave a huge impression when they focus on one polished mechanic and a clear artistic direction.',
                        'published' => true,
                    ],
                ],
            ],
        ];

        foreach ($authors as $authorData) {
            $posts = $authorData['posts'];
            unset($authorData['posts']);

            $author = Author::updateOrCreate(
                ['email' => $authorData['email']],
                $authorData
            );

            foreach ($posts as $postData) {
                Post::updateOrCreate(
                    [
                        'author_id' => $author->id,
                        'title' => $postData['title'],
                    ],
                    [
                        'content' => $postData['text'],
                        'description' => $postData['text'],
                        'published' => $postData['published'],
                    ]
                );
            }
        }
    }
}
