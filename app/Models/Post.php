<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Platform\Dashboard;

class Post extends Model
{
    use HasFactory, AsSource, Filterable, Attachable;


    // public function images() {
    //     return $this->hasMany(Image::class);
    // }

    public function owner() {
        return $this->belongsTo(User::class , "user_id");
    }

    // public function featuredImage() {
    //     return $this->morphTo(
    //         Dashboard::model(Attachment::class),
    //         'attachmentable',
    //         'attachmentable',
    //         'attachmentable_id',
    //         'attachment_id'
    //     );;
    // }

    public function featuredImage() {
        return $this->hasOne(Attachment::class , "id" , "featured_image_id");
    }
}
