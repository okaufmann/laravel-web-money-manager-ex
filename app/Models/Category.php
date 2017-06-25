<?php

namespace App\Models;

class Category extends Model
{
    protected $fillable = ['name'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentCategory()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function defaultForPayees()
    {
        return $this->hasMany(Payee::class, 'last_category_id');
    }
}
