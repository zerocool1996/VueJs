<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAccountRequest extends FormRequest
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
            'firstname'     => 'required|max:50',
            'lastname'      => 'required|max:50',
            'gender'        => 'required|regex:/^[1-3]$/',
            'address'       => 'required|max:255',
            'confirm'       => 'required|max:50|min:3|same:password',
            'img'           => 'image|mimes:jpeg,png,jpg,gif',
            'role_group_id'     => 'exists:role_group,id'
        ];
    }

    public function messages()
    {
        return [
            'gender.required' => 'Sex is required',
        ];
    }
}
