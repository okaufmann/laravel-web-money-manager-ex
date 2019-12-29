<?php

namespace App\Transformers;

use App\Models\Category;
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
        $data = [
            'id'   => $payee->id,
            'name' => $payee->name,
        ];

        $category = null;
        if ($category = Category::find($payee->last_category_id)) {
            if ($category) {
                if ($category->parent_id) {
                    $data['category_id'] = $category->parent_id;
                    $data['sub_category_id'] = $category->id;
                } else {
                    $data['category_id'] = $category->id;
                }
            }
        }

        return $data;
    }
}
