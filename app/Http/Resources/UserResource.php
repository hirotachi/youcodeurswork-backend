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

        $showMore = $request->routeIs(["me", "users.show", "user.updateProfile"]);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when(auth()->id() === $this->id, $this->email),
            "site" => $this->when($showMore, $this->site),
            "avatar" => $this->avatar,
            "description" => $this->when($showMore, $this->description),
            "social_accounts" => $this->when($showMore, json_decode($this->social_accounts)),
            "headline" => $this->when($showMore, $this->headline ?? $this->role),
        ];
    }
}
