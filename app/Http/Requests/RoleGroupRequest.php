<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleGroupRequest extends FormRequest
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
            'title'         => 'required|unique:role_group,title,'. $this->id,
            'description'   => 'required',
            'roles'         => 'required',
            'roles.*'       => 'exists:roles,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required'        => 'Enter : Title  ',
            'title.unique'          => 'This title already exist !  ',
            'description.required'  => 'Enter : Description',
            'roles.required'        => 'Please select role'
        ];
    }
}
