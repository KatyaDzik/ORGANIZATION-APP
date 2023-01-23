<?php

namespace App\Http\Requests;

use App\Rules\INN;
use App\Rules\SNILS;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserPutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules($id)
    {
        return [
            'firstname' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
            'middlename' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
            'lastname' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
            'birthday' => ['nullable', 'string', 'date', 'before:today',],
            'inn' => ['required', 'string', 'digits:12' , new INN(),  Rule::unique('users', 'inn')->ignore($id, 'id')],
            'snils' => ['required', 'string', 'digits:11',  Rule::unique('users', 'snils')->ignore($id, 'id'), new SNILS()]
        ];
    }
}
