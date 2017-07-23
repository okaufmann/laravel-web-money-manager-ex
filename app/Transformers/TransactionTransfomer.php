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
        $data = [
            'id'                => $item->id,
            'account_name'      => $item->account_name,
            'to_account_name'   => $item->to_account_name,
            'payee_name'        => $item->payee_name,
            'category_name'     => $item->category_name,
            'sub_category_name' => $item->sub_category_name,
            'category_names'    => $item->sub_category_name ? $item->category_name.' / '.$item->sub_category_name : $item->category_name,
            'amount'            => round($item->amount, 2),
            'notes'             => $item->notes,
            'transaction_date'  => $item->transaction_date ? $item->transaction_date->toIso8601String() : null,
            'has_attachments'   => $item->hasAttachments(),
            'created_at'        => $item->created_at->toIso8601String(),
            'updated_at'        => $item->updated_at->toIso8601String(),
        ];

        if ($item->status) {
            $data['status'] = [
                'id'   => $item->status->id,
                'name' => __('mmex.'.$item->status->name),
                'slug' => $item->status->slug,
            ];
        }

        if ($item->type()) {
            $data['type'] = [
                'id'   => $item->type->id,
                'name' => __('mmex.'.$item->type->name),
                'slug' => $item->type->slug,
            ];
        }

        return $data;
    }
}
