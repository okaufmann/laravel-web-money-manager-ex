<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'status_ids'    => 'array|required',
            'status_values' => 'array|required',
            'type_ids'      => 'array|required',
            'type_values'   => 'array|required',
        ];
    }

    public function getStatusAndTypes()
    {
        $status_ids = collect($this->get('status_ids'));
        $status_values = collect($this->get('status'));
        $status = $status_ids->combine($status_values);

        $types_ids = collect($this->get('types_id'));
        $types_values = collect($this->get('types'));
        $types = $types_ids->combine($types_values);

        return [$status, $types];
    }
}
