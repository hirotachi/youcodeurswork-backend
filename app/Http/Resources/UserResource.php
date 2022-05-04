<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when(auth()->id() === $this->id, $this->email),
            "site" => $this->site,
            "avatar" => $this->avatar,
            "description" => $this->description,
            "social_accounts" => json_decode($this->social_accounts),
            "headline" => $this->headline ?? $this->role,
        ];
    }
}
