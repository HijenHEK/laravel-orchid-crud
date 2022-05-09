<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{

    static $id;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        self::$id = Post::latest()->first()->id ?? 1;

        return [
            "title" => $this->faker->sentence(),
            "body" => $this->faker->sentences(rand(1, 5), true),
            "user_id" => User::all()->random()->id,
            "featured_image_id" => Image::create(["path" => $this->faker->imageUrl(), "post_id" => self::$id]),
        ];
    }
}
