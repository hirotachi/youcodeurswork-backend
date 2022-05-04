<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Technology;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{

    public function index()
    {
        return new ProjectCollection(Project::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProjectRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProjectRequest $request)
    {
        try {
            DB::beginTransaction();
            $project = Project::create($request->all());
            $this->associateData($project, "tags", $request);
            $this->associateData($project, "technologies", $request);
            DB::commit();
            return response()->json([
                'message' => 'success',
                'project' => new ProjectResource($project),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function associateData($project, $key, StoreProjectRequest $request)
    {
        $data = $request->input($key);
        if (!$data) {
            return;
        }
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

    public function show($id)
    {

        try {
            return new ProjectResource(Project::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProjectRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateProjectRequest $request, int $id)
    {


        try {
            DB::beginTransaction();
            try {
                $project = Project::findOrFail($id);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            $this->authorize('update', $project);
            $this->associateData($project, "tags", $request);
            $this->associateData($project, "technologies", $request);
            $project->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'success',
                'project' => new ProjectResource($project),
            ], 200);
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        try {
            DB::beginTransaction();
            try {
                $project = Project::firstOrFail($id);
                $project->technologies()->detach();
                $project->tags()->detach();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            $this->authorize('delete', $project);
            $project->delete();
            DB::commit();
            return response()->json(
                [
                    'message' => 'success',
                ]
                , 204);
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
