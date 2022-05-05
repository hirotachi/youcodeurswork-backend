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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->when($request->route()->job == $this->id, $this->description, $description),
            'location' => $this->location,
            'image' => $this->image,
            'type' => $this->type,
            'user' => new UserResource($this->user),
            "tags" => TagResource::collection($this->tags),
            "technologies" => TechnologyResource::collection($this->technologies),
            'company_name' => $this->company_name,
            'company_site' => $this->company_site,
            'apply_by' => $this->apply_by,
            "apply_to" => $this->apply_to,
            'company_logo' => $this->company_logo,
            'remote' => $this->remote,
            'created_at' => Helpers::time_elapsed_string($this->created_at),
            'updated_at' => $this->updated_at,
        ];
    }
}
