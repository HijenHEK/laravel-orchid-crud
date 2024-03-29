<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Attachment;
use Orchid\Platform\Dashboard;

/**
 * Trait Attachable.
 */
trait Attachable
{
    /**
     * @param string|null $group
     *
     * @return MorphToMany
     */
    public function attachment(string $group = null): MorphToMany
    {
        $query = $this->morphToMany(
            Dashboard::model(Attachment::class),
            'attachmentable',
            'attachmentable',
            'attachmentable_id',
            'attachment_id'
        );

        if ($group !== null) {
            $query->where('group', $group);
        }

        return $query
            ->orderBy('sort');
    }
}
