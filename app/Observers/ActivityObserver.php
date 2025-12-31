<?php

namespace App\Observers;

use App\Models\Activity;
use Illuminate\Support\Facades\Storage;

class ActivityObserver
{
    public function updating(Activity $activity): void
    {
        if ($activity->isDirty('thumbnail') && $activity->getOriginal('thumbnail')) {
            Storage::disk('activities')->delete($activity->getOriginal('thumbnail'));
            // Storage::disk('activities')->delete('thumbs/' . $activity->getOriginal('photo'));
        }
    }

    public function deleting(Activity $activity): void
    {
        if ($activity->photo) {
            Storage::disk('activities')->delete($activity->photo);
            Storage::disk('activities')->delete('thumbs/' . $activity->photo);
        }
    }
}
