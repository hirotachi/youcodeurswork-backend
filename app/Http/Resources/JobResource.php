<?php

namespace App\Http\Resources;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Job */
class JobResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
//         remove new lines and spaces
        $description = preg_replace('/\s+/', ' ', strip_tags($this->description));
        $maxLength = 300;
        if (strlen($description) > $maxLength) {
            $description = substr($description, 0, $maxLength).'...';
        }
        $showMore = $request->routeIs("jobs.show");
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->when($showMore, $this->description, $description),
            'location' => $this->location,
            "category" => $this->category,
            'image' => $this->when($showMore, $this->image),
            'type' => $this->type,
            'user' => new UserResource($this->user),
            "tags" => $this->when($showMore, TagResource::collection($this->tags)),
            "technologies" => $this->when($showMore, TechnologyResource::collection($this->technologies)),
            'company_name' => $this->company_name,
            'company_site' => $this->when($showMore, $this->company_site),
            'apply_by' => $this->when($showMore, $this->apply_by),
            "apply_to" => $this->when($showMore, $this->apply_to),
            'company_logo' => $this->company_logo,
            'remote' => $this->remote,
            'created_at' => Helpers::time_elapsed_string($this->created_at),
        ];
    }
}
