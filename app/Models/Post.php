<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Post extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function featuredImage() {
        return $this->belongsTo(Image::class , "featured_image_id");
    }
}
