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
        $showMore = $request->routeIs("projects.show");

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->when($showMore, $this->description),
            'images' => is_string($this->images) ? json_decode($this->images) : $this->images,
            "creator" => new UserResource($this->user),
            'tags' => $this->when($showMore, TagResource::collection($this->tags)),
            'technologies' => $this->when($showMore, TechnologyResource::collection($this->technologies),
                TechnologyResource::collection($this->technologies()->limit(3)->get())),
            "likesCount" => $this->likers()->count(),
            "liked" => $this->likers()->where('user_id', auth()->id())->exists(),
            'repo_link' => $this->when($showMore, $this->repo_link),
            'created_at' => $this->created_at,
        ];
    }
}
