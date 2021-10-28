<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends Model
{
    use HasFactory;

    /**
     * Timestamps to be set.
     *
     * @var string[]
     */
    public $timestamps = ["created_at"];

    /**
     * Disable model update timestamp.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /**
     * Get storage
     *
     * @return BelongsTo
     */
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    /**
     * Get products
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class)
            ->withPivot('count', 'remainder', 'is_decreased', 'is_increased', 'is_deleted', 'is_created');
    }
}
