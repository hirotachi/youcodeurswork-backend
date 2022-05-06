<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TechnologyCollection;
use App\Models\Tag;
use App\Models\Technology;

/**
 * Common controllers functionalities
 *
 */
trait CommonTrait
{
    private function table()
    {
        return "table";
    }

    public function associateData($project, $key, $request)
    {
        $data = $request->validated($key);
        if ($data === null) {
            return;
        }
        if (count($data) === 0) {
            $project->{$key}()->sync($data);
            return;
        }
        $data = array_map(function ($item) {
            return ["name" => strtolower(trim($item, " "))];
        }, $data);

        $request->offsetUnset($key);
        if ($key === 'tags') {
            Tag::upsert($data, ['name']);
            $data = Tag::whereIn('name', $data)->get();
        } else {
            if ($key === 'technologies') {
                Technology::upsert($data, ['name']);
                $data = Technology::whereIn('name', $data)->get();
            }
        }
        $ids = $data->pluck('id')->toArray();
        $project->{$key}()->sync($ids);
    }

    public function tags()
    {
        return new TagCollection(Tag::has($this->table())->paginate(10));
    }

    public function technologies()
    {
        return new TechnologyCollection(Technology::has($this->table())->paginate(10));
    }
}
