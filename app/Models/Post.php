<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Post extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    // public function images() {
    //     return $this->hasMany(Image::class);
    // }

    public function owner() {
        return $this->belongsTo(User::class , "user_id");
    }

    public function featuredImage() {
        return $this->belongsTo(Attachment::class , "featured_image_id");
    }
}
