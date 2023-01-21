<?php

namespace App\Http\Requests;

use App\Rules\OGRN;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrgPutRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules($id)
    {
        return [
            'name' => ['required', 'min:2', 'max:255', 'string', Rule::unique('organizations', 'name')->ignore($id, 'id')],
            'ogrn' => ['required', 'string', 'digits:13', new OGRN(), Rule::unique('organizations', 'ogrn')->ignore($id, 'id')],
            'oktmo' => ['required', 'string', 'digits:11']
        ];
    }
}
