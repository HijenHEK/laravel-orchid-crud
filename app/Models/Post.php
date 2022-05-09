<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

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
