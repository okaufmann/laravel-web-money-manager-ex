<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    private $defaultLocale = 'en_US';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mmex_guid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->attributes['is_admin'];
    }

    public function getLocaleAttribute()
    {
        $locale = $this->attributes['locale'] ?? $this->defaultLocale;

        return $locale;
    }

    public function getLocaleKendoAttribute()
    {
        $locale = $this->locale;

        $locale = str_replace('_', '-', $locale);

        return $locale;
    }

    public function getLocaleMomentAttribute()
    {
        $locale = $this->locale;

        $locale = str_replace('_', '-', $locale);
        $locale = strtolower($locale);

        // TODO: ugly workaround for different locale styles
        $locale = $locale == 'de-de' ? 'de' : $locale;
        $locale = $locale == 'en-us' ? 'en' : $locale;

        return $locale;
    }

    public function getLocaleDateFormatAttribute()
    {
        $format = locale_dateformat($this->locale);

        return $format;
    }

    public function getLanguageAttribute()
    {
        $locale = $this->locale;
        $language = explode('_', $locale)[0];

        return $language;
    }

    public function getApiTokenAttribute($value)
    {
        if (empty($value)) {
            $value = str_random(60);
            $this->api_token = $value;
            $this->save();
        }

        return $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payees()
    {
        return $this->hasMany(Payee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
