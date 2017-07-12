<?php

namespace App\Models;

class Category extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * Scope a query to only include sub categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRootCategories($query)
    {
        return $query->where('parent_id', null);
    }

    /**
     * Scope a query to only include sub categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Returns categories of the given user.
     *
     * @param $query
     * @param User $user
     *
     * @return mixed
     */
    public function scopeOfUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function defaultForPayees()
    {
        return $this->hasMany(Payee::class, 'last_category_id');
    }
}
