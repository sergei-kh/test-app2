<?php

namespace App\Services;

use App\Contracts\StoryManager;
use App\Models\Storage;
use App\Models\Story;

class StoryManagerService implements StoryManager
{
    public function create(Storage $storage, array $products, bool $isFrom, int $id): void
    {
        $story = new Story();
        $story->is_from = $isFrom;
        $story->target_id = $id;
        $storage->stories()->save($story);
        $story->products()->sync($products);
    }
}
