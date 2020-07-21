<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email'         => 'email|required|max:255|unique:users,email,'. $this->id,
            'password'      => 'required|max:50|min:3',
            'first_name'     => 'required|max:50',
            'last_name'      => 'required|max:50',
            'gender_id'        => 'required|regex:/^[1-3]$/',
            'address'       => 'required|max:255',
            'confirm'       => 'required|max:50|min:3|same:password',
            'tel'           => 'numeric'
        ];
    }

    public function messages()
    {
        return [
            // 'gender.required' => 'Sex is required',
        ];
    }
}
