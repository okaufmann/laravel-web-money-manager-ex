<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $dateFormat = auth()->user()->localeDateFormat;
        $size = $this->getMaxFileUploadSizeInKb();

        return [
            'transaction_date'   => 'date_format:'.$dateFormat.'|nullable',
            'transaction_status' => 'integer',
            'transaction_type'   => 'required|integer',
            'account'            => 'required|integer',
            'to_account'         => 'sometimes|required|integer',
            'payee'              => 'sometimes|required|integer',
            'category'           => 'required|integer',
            'subcategory'        => 'integer',
            'amount'             => 'required|numeric',
            'amount'             => 'required|numeric|min:0|max:999999',
            'attachments.*'      => 'max:'.$size,
        ];
    }

    private function getMaxFileUploadSizeInKb()
    {
        $uploadSizes = [ini_get('upload_max_filesize'), ini_get('post_max_size')];

        $uploadSizes = array_map(function ($item) {
            $metric = strtoupper(substr($item, -1));

            switch ($metric) {
                case 'K':
                    return (int) $item * 1024;
                case 'M':
                    return (int) $item * 1048576;
                case 'G':
                    return (int) $item * 1073741824;
                default:
                    return (int) $item;
            }
        }, $uploadSizes);

        return min($uploadSizes) * 1024;
    }
}
