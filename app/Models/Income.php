<?php

namespace App\Models;

use App\Scopes\UserVisibilityScope;
use App\Traits\HasActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Income extends Model
{
    use HasFactory, AsSource, HasActivity;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'amount',
        'entry_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['category'];

    protected static function booted()
    {
        static::addGlobalScope(new UserVisibilityScope);
    }

    /**
     * Scope a query to only include incomes of a given value.
     *
     * @param Builder $query
     * @param string $value
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $value): Builder
    {
        return $query->where('title', 'like', '%'.$value.'%')
            ->orWhere('amount', 'like', '%'.$value.'%');
    }

    /**
     * Get the user that owns the income.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the income.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function totalIncome(): float
    {
        return floatval($this->where('user_id', auth()->id())->sum('amount'));
    }
}
