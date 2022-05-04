<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Project */
class ProjectResource extends JsonResource
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
            'description' => $this->description,
            'images' => json_decode((string) $this->images),
            'tags' => TagResource::collection($this->tags),
            'technologies' => TechnologyResource::collection($this->technologies),
            "likesCount" => $this->likers()->count(),
            "liked" => $this->likers()->where('user_id', auth()->id())->exists(),
            'repo_link' => $this->repo_link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
