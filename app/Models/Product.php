<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get warehouses where this product is
     *
     * @return BelongsToMany
     */
    public function storages(): BelongsToMany
    {
        return $this
            ->belongsToMany(Storage::class)
            ->withPivot('count');
    }

    /**
     * Get stories for product
     *
     * @return BelongsToMany
     */
    public function stories(): BelongsToMany
    {
        return $this
            ->belongsToMany(Story::class)
            ->withPivot('count', 'remainder', 'is_decreased', 'is_increased', 'is_deleted', 'is_created');
    }
}
