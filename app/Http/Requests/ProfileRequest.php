<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|max:255|min:3',
            'headline' => 'string',
            "description" => 'string',
            "social_accounts" => 'array',
            "social_accounts.*" => 'url',
            "avatar" => 'url|ends_with:png,jpg,jpeg,gif',
            "site" => 'url',
        ];
    }

}
