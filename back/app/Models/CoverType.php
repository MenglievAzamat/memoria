<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $image_link
 */
class CoverType extends Model
{
    protected $fillable = [
        'name',
        'image_link'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
