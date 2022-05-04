<?php

namespace App\Http\Requests;

class UpdateProjectRequest extends StoreProjectRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        unset($rules['user_id']);
        return [
            ...$rules,
            "name" => "string|max:120",
        ];
    }

}
