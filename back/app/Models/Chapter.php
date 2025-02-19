<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $book_id
 * @property string $title
 * @property string $question
 * @property string $text
 * @property int $last_page
 * @property Book $book
 * @property Collection $pages
 */
class Chapter extends Model
{
    protected $fillable = [
        'title',
        'question',
        'text'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function countPages(): int
    {
        $result = [];

        if ($this->text) {
            $words = preg_split('/\s/', $this->text);
            $page = '';

            foreach ($words as $word) {
                $length = count($result) === 0 ? 500 : 534;

                if (mb_strlen($word) + mb_strlen($page) <= $length) {
                    $page .= $word . ' ';
                } else {
                    $result[] = trim($page);
                    $page = '';
                }
            }

            if (mb_strlen($page) !== 0) {
                $result[] = trim($page);
            }
        }

        return count($result);
    }

    public function previousChapterId(): ?int
    {
        $chapters = Chapter::query()->where('book_id', $this->book_id)->pluck('id');
        $currentIndex = $chapters->filter(fn ($chapterId) => $chapterId === $this->id)->keys();

        return $chapters[$currentIndex->first() - 1] ?? null;
    }
}
