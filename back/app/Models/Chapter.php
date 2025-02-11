<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $book_id
 * @property string $title
 * @property string $question
 * @property Book $book
 * @property Collection $pages
 */
class Chapter extends Model
{
    protected $fillable = [
        'title',
        'question'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
