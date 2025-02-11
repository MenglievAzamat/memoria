<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $chapter_id
 * @property string $question
 * @property string $text
 * @property string $image
 * @property int $page_number
 * @property Chapter $chapter
 */
class Page extends Model
{
    protected $fillable = [
        'question',
        'text',
        'image',
        'page_number',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
