<?php

namespace App\Models;

use App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    public function setTransactionDateAttribute($value)
    {
        if (!$value) {
            return;
        }

        $format = 'm/d/Y';
        if (App::getLocale() == 'de') {
            $format = 'd.m.Y';
        }

        $date = Carbon::createFromFormat($format, $value);
        $date->hour(0);
        $date->minute(0);
        $date->second(0);
        $this->attributes['transaction_date'] = $date;
    }

    public function hasAttachments()
    {
        return $this->hasMedia('attachments');
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
