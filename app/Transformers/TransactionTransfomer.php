<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransfomer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $item)
    {
        return [
            'id'                => $item->id,
            'status'            => [
                'id'   => $item->status->id,
                'name' => $item->status->name,
                'slug' => $item->status->slug
            ],
            'type'              => [
                'id'   => $item->type->id,
                'name' => $item->type->name,
                'slug' => $item->type->slug
            ],
            'account_name'      => $item->account_name,
            'to_account_name'   => $item->to_account_name,
            'payee_name'        => $item->payee_name,
            'category_name'     => $item->category_name,
            'sub_category_name' => $item->sub_category_name,
            'amount'            => round($item->amount, 2),
            'notes'             => $item->notes,
            'created_at'        => $item->created_at->toIso8601String(),
            'updated_at'        => $item->updated_at->toIso8601String()
        ];
    }
}
