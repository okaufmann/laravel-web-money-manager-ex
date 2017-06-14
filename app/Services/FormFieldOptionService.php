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

class FormFieldOptionService
{
    public function getValues($model)
    {
        // TODO: cache
        $values = $model::all()->values();

        return $values;
    }
}
