<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cover_type_id
 * @property int $book_status_id
 * @property string $title
 * @property string $subtitle
 * @property boolean $open
 * @property-read User $user
 * @property-read CoverType $coverType
 * @property-read Questionnaire $questionnaire
 * @property-read BookStatus $bookStatus
 * @property-read Collection $chapters
 */
class Book extends Model
{
    protected $fillable = [
        'user_id',
        'cover_type_id',
        'book_status_id',
        'title',
        'subtitle',
    ];

    protected $with = [
        'questionnaire'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coverType(): BelongsTo
    {
        return $this->belongsTo(CoverType::class);
    }

    public function questionnaire(): HasOne
    {
        return $this->hasOne(Questionnaire::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function bookStatus(): BelongsTo
    {
        return $this->belongsTo(BookStatus::class);
    }

    public function pagesCount() {
        return $this->chapters()
            ->withCount('pages')
            ->get()
            ->reduce(function ($acc, $curr) {
                return $acc + $curr->pages_count;
            }, 0);
    }

    public function rearrangePageNumbers(): void
    {
        /** @var Collection $pages */
        $pages = $this->chapters->reduce(function (Collection $acc, Chapter $chapter) {
            $acc = $acc->merge($chapter->pages);

            return $acc;
        }, collect());

        $pageNumber = 1;

        $pages->each(function (Page $page) use (&$pageNumber){
            $page->page_number = $pageNumber++;
            $page->save();
        });
    }
}
