<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Transaction extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;

    protected $fillable = ['transaction_date', 'account_name', 'to_account_name', 'payee_name', 'category_name', 'sub_category_name', 'amount', 'notes'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at', 'updated_at', 'transaction_date'];

    /**
     * Set the user's first name.
     *
     * @param string $value
     *
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['transaction_date'] = strtolower($value);
    }

    public function getAccountIdAttribute()
    {
        $account = Account::where('name', $this->account_name)->first();

        if ($account) {
            return $account->id;
        }

        return null;
    }

    public function getToAccountIdAttribute()
    {
        $account = Account::where('name', $this->to_account_name)->first();

        if ($account) {
            return $account->id;
        }

        return null;
    }

    public function getPayeeIdAttribute()
    {
        $payee = Payee::where('name', $this->payee_name)->first();

        if ($payee) {
            return $payee->id;
        }

        return null;
    }

    public function getCategoryIdAttribute()
    {
        $category = Category::where('name', $this->category_name)->first();

        if ($category) {
            return $category->id;
        }

        return null;
    }

    public function getSubCategoryIdAttribute()
    {
        $category = Category::where('name', $this->sub_category_name)->first();

        if ($category) {
            return $category->id;
        }

        return null;
    }

    public function status()
    {
        return $this->belongsTo(TransactionStatus::class);
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function addAttachment($file, $keepOriginal = false)
    {
        if (is_string($file)) {
            $file = basename($file);
        }

        $media = $this->addMedia($file)
            ->usingFileName('Transaction_'.$this->id.'_'.$fileName);

        if ($keepOriginal) {
            $media->preservingOriginal();
        }

        $media->toMediaCollection('attachments');
    }
}
