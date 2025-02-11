<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $book_id
 * @property string $for_who
 * @property string $self_info
 * @property string $summary
 * @property-read Book $book
 */
class Questionnaire extends Model
{
    protected $fillable = [
        'book_id',
        'for_who',
        'self_info',
        'summary',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
