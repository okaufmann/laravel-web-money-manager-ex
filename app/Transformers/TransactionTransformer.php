<?php
/**
 * Created by PhpStorm.
 * User: okaufmann
 * Date: 23.10.2016
 * Time: 01:14.
 */

namespace App\Transformers;

use App\Models\Transaction;

class TransactionTransformer extends Transformer
{
    public function transform($item)
    {
        /* @var Transaction $item */
        return [
            'ID'          => $item->id,
            'Date'        => $item->date,
            'Account'     => $item->account_name,
            'ToAccount'   => $item->to_account_name,
            'Status'      => $item->status->slug,
            'Type'        => $item->type->name,
            'Payee'       => $item->payee_name,
            'Category'    => $item->category_name,
            'SubCategory' => $item->sub_category_name,
            'Amount'      => floatval($item->amount),
            'Notes'       => $item->notes,
            'Attachments' => $item->getMedia('attachments')
                ->map(function ($mediaItem) {
                    return $mediaItem->file_name;
                })->implode(','), ];
    }
}
