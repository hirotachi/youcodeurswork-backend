<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Technology;

class AssociationController extends Controller
{
    static public function associateData($project, $key, $request)
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
}
