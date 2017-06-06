<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Transaction extends Model
{
    use SoftDeletes;
    use HasMediaTrait;

    protected $fillable = ['account_name', 'to_account_name', 'payee_name', 'category_name', 'sub_category_name', 'amount', 'notes'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function getDateAttribute()
    {
        return $this->created_at->toDateString();
    }

    public function status()
    {
        return $this->belongsTo(TransactionStatus::class);
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class);
    }
}
