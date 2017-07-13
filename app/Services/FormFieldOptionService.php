<?php
/*
 * laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 */

namespace App\Services;

use Auth;

class FormFieldOptionService
{
    public function getValues($model, $all = false)
    {
        if ($all) {
            $values = $model::all()->values();
        } else {
            $values = $model::where(['user_id' => Auth::user()->id])->get()->values();
        }

        $values->only(['id', 'name', 'slug'])
            ->transform(function ($value) {
                $value['name'] = _($value['name']);
                return $value;
            });

        return $values;
    }
}
