<?php

namespace App\Transformers\Mmex;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;
use Spatie\MediaLibrary\Media;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Transaction $item
     *
     * @return array
     */
    public function transform(Transaction $item)
    {
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
            'Amount'      => (string)floatval($item->amount),
            'Notes'       => $item->notes,
            'Attachments' => $item->getMedia('attachments')
                ->map(function (Media $mediaItem) {
                    return $mediaItem->file_name;
                })->implode(';'),];
    }
}
