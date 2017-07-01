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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $size = $this->getMaxFileUploadSizeInKb();

        return [
            'id'                 => 'integer',
            'transaction_date'   => 'date|nullable',
            'transaction_status' => 'integer',
            'transaction_type'   => 'integer',
            'account'            => 'integer',
            'payee'              => 'integer',
            'category'           => 'required|integer',
            'subcategory'        => 'integer',
            'amount'             => 'numeric',
            'notes'              => 'string|nullable',
            'attachments.*'      => 'max:'.$size
        ];
    }

    private function getMaxFileUploadSizeInKb()
    {
        $uploadSizes = [ini_get('upload_max_filesize'), ini_get('post_max_size')];

        $uploadSizes = array_map(function ($item) {
            $metric = strtoupper(substr($item, -1));

            switch ($metric) {
                case 'K':
                    return (int)$item * 1024;
                case 'M':
                    return (int)$item * 1048576;
                case 'G':
                    return (int)$item * 1073741824;
                default:
                    return (int)$item;
            }
        }, $uploadSizes);

        return min($uploadSizes) * 1024;
    }
}
