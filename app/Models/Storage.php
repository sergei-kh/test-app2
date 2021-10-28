<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Storage extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the goods that are in this warehouse
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class)
            ->withPivot('count');
    }

    /**
     * Get warehouse history
     *
     * @return HasMany
     */
    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }
}
