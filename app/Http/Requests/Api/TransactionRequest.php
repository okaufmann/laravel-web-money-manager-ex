<?php

namespace App\Http\Requests\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'account.exists' => 'no entry found for account',
            'transaction_status.exists' => 'no entry found for transaction_status',
            'transaction_type.exists' => 'no entry found for transaction_type',
            'category.exists' => 'no entry found for category',
            'subcategory.exists' => 'no entry found for subcategory'
        ];
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
            'transaction_status' => 'string|exists:transaction_status,name',
            'transaction_type'   => 'required|string|exists:transaction_types,name',
            'account'            => [
                'required',
                'string',
                Rule::exists('accounts', 'name')->where('user_id', auth()->user()->id),
            ],
            'to_account'         => 'sometimes|required|string',
            'payee'              => 'sometimes|required|string',
            'category'           => [
                'required',
                'string',
                Rule::exists('categories', 'name')->where('user_id', auth()->user()->id)->whereNull('parent_id'),
            ],
            'subcategory'        => [
                'string',
                Rule::exists('categories', 'name')->where('user_id', auth()->user()->id)->whereNotNull('parent_id'),
            ],
            'amount'             => 'required|numeric|min:0|max:999999',
            'notes'              => 'string|nullable',
        ];
    }
}
