<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Faker\Generator as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Orchid\Attachment\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            CategorySeeder::class
        ]);

        foreach (Post::all() as $key => $post) {
            $image = \Illuminate\Http\UploadedFile::fake()->create('image.png');
            $file = new File($image );
            $attachment = $file->load();
            $post->attachment()->sync($attachment);
            $post->featured_image = $attachment->url;
            $post->category_id = Category::all()->random()->id;
            $post->save();
        }


    }
}
