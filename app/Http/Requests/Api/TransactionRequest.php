<?php

namespace App\Http\Requests\Api;

use Carbon\Carbon;
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
        return [
            'transaction_date'   => 'nullable|date|date_format:'.Carbon::ATOM,
            'transaction_status' => 'string',
            'transaction_type'   => 'required|string',
            'account'            => 'required|string',
            'to_account'         => 'string',
            'payee'              => 'required|string',
            'category'           => 'required|string',
            'subcategory'        => 'string',
            'amount'             => 'required|numeric|min:0',
            'notes'              => 'string|nullable',
        ];
    }
}
