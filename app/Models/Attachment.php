<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Orchid\Attachment\Models\Attachment as OrchidAttachment;
use Orchid\Platform\Dashboard;

/**
 * Class Attachment.
 */
class Attachment extends OrchidAttachment
{
    public function __construct()
    {
        parent::__construct();
        array_push($this->appends, 'originalUrl');
    }

    public function getOriginalUrlAttribute()
    {
        return $this->isImage() ? str_replace("/thumbnail/", "/original/", $this->relativeUrl) : null;
    }

    public function isImage()
    {
        return str_contains($this->mime, 'image');
    }
}
