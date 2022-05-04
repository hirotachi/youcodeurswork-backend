<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:120|min:5',
            'description' => 'required|string|max:10000|min:10',
            'location' => 'required|string',
            'image' => 'nullable|string|ends_with:.jpg,.jpeg,.png,.gif',
            'type' => 'required|string|in:full-time,part-time,freelance,internship',
            'user_id' => 'required|integer',
            'company_name' => 'required|min:5',
            'company_site' => 'required|url',
            'apply_by' => 'required|in:email,url',
            'company_logo' => 'string|nullable|ends_with:.jpg,.jpeg,.png,.gif',
            'remote' => 'required|boolean',
            "tags" => 'array',
            "tags.*" => 'string|required|max:20|min:1',
            "technologies" => 'array',
            "technologies.*" => 'string|required|max:25|min:1',
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'user_id' => auth()->id(),
        ]);
    }


}
