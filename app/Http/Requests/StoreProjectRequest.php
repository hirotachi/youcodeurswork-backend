<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required|max:120|min:5',
            'description' => 'nullable',
            'images' => 'array|required|min:1',
            'repo_link' => 'nullable|url',
            'user_id' => 'required|integer',
            "images.*" => 'url|required|ends_with:.png,.jpg,.jpeg,.gif,.svg,.webp',
            "tags" => 'array',
            "tags.*" => 'string|required|max:20|min:1',
            "technologies" => 'array',
            "technologies.*" => 'string|required|max:25|min:1',
        ];
    }

    public function messages()
    {
        return [
            "images.*.ends_with" => "Image must be a valid image",
        ];

    }


    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }


}
