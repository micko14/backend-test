<?php

use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $news = [
            [
                'title' => 'First Title',
                'author_id' => '1',
                'article' => 'First Article',
                'is_published' => '1',

            ],
            [
                'title' => 'Second Title',
                'author_id' => '1',
                'article' => 'Second Article',
                'is_published' => '1',

            ],
            [
                'title' => 'Third Title',
                'author_id' => '1',
                'article' => 'Third Article',
                'is_published' => '0',

            ],
        ];

        foreach ($news as $new) {
            \App\News::create($new);
        }
    }
}
