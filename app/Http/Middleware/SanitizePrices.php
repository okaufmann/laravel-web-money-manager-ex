<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class SanitizePrices extends TransformsRequest
{
    /**
     * The attributes that should be sanitized.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        preg_match('/[1-9]\d*(\,\d+)?/', $value, $matches);

        if (count($matches) == 1) {
            $value = str_replace(',', '.', $value);

            return $value;
        }

        return $value;
    }
}
