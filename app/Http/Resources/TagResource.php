<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->routeIs("projects.tags")) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                "projects_count" => $this->projects()->count(),
            ];
        }
        if ($request->routeIs("jobs.tags")) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                "jobs_count" => $this->jobs()->count(),
            ];
        }

        return $this->name;
    }
}
