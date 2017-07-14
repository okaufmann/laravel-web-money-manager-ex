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
    public function getMasterData($model)
    {

        $values = $model::all();

        $values = $values->values()
            ->map(function ($value) {
                return [
                    'name' => __('mmex.'.$value->name),
                    'id'   => $value->id,
                    'slug' => $value->slug,
                ];
            })
            ->values();

        return $values;
    }

    public function getUserData($model)
    {
        $values = $model::where(['user_id' => Auth::user()->id])->get()->values();

        return $values;
    }
}
