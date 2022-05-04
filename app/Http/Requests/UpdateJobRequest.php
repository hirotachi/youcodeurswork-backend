<?php

namespace App\Http\Requests;

class UpdateJobRequest extends StoreJobRequest
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
        return $rules;
    }
}
