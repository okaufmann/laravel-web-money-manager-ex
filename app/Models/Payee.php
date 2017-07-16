<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Payee extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $dates = ['last_used_at'];

    public function lastCategoryUsed()
    {
        return $this->belongsTo(Category::class, 'last_category_id');
    }
}
