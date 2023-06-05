<?php

namespace App\Models;

use App\Scopes\UserVisibilityScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Orchid\Screen\AsSource;

class Activity extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'user_id',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new UserVisibilityScope());
    }

    /**
     * Get the parent subject model (income or expense).
     *
     * @return MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
