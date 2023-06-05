<?php

namespace App\Models;

use App\Scopes\UserVisibilityScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'user_id',
        'name',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean'
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
     * Scope a query to only include categories of a given value.
     *
     * @param Builder $query
     * @param string $value
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $value): Builder
    {
        return $query->where('name', 'like', '%'.$value.'%');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'category_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
