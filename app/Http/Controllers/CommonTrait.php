<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Http\Resources\TechnologyCollection;
use App\Models\Tag;
use App\Models\Technology;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Common controllers functionalities
 *
 */
trait CommonTrait
{
    abstract private function model(): Model;

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
        return new TagCollection(Tag::has($this->model()->getTable())->paginate(10));
    }

    public function technologies()
    {
        return new TechnologyCollection(Technology::has($this->model()->getTable())->paginate(10));
    }

    public function destroy($id)
    {
        $array = explode('\\', get_class($this->model()));
        $entityName = array_pop($array);
        try {
            DB::beginTransaction();
            try {
                $entity = $this->model()->findOrFail($id);
                $entity->technologies()->detach();
                $entity->tags()->detach();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => "$entityName not found",
                ], 404);
            }
            $this->authorize('delete', $entity);
            $entity->delete();
            DB::commit();
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json(
            [
                'message' => 'success',
            ]
            , 200);
    }
}
