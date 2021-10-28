<?php

namespace App\Contracts;

use App\Models\Storage;

interface StoryManager
{
    /**
     * Record changes in history
     *
     * @param Storage $storage
     * @param array $products
     * @param bool $isFrom
     * @return void
     */
    public function create(Storage $storage, array $products, bool $isFrom, int $id): void;
}
