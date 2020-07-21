<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'          => 'required|max:50|min:2|unique:products,name,'. $this->id,
            'price'         => 'required|max:9',
            'author_id'     => 'required|exists:authors,id',
            'img'           => 'required',
            'categories.*'  => 'exists:categories,id',
            'content'       => 'required',
            'categories'    => 'required'
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
