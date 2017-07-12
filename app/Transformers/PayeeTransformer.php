<?php

namespace App\Transformers;

use App\Models\Payee;
use League\Fractal\TransformerAbstract;

class PayeeTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Payee $payee)
    {
        return [
            'id'   => $payee->id,
            'name' => $payee->name
        ];
    }
}
