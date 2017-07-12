<?php

namespace App\Models;

class Payee extends Model
{
    protected $fillable = ['name'];

    public function lastCategoryUsed()
    {
        return $this->belongsTo(Category::class, 'last_category_id');
    }
}
