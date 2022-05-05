<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\stringContains;

class ProjectController extends Controller
{

    static public string $role = 'student';

    public function index()
    {
        $projects = Project::where("name", "LIKE", "%".request("q")."%")->orderByDesc("created_at");
        $relations = ["tags", "technologies"];
        foreach ($relations as $relation) {
            if (request()->has($relation) && request($relation) != "") {
                $projects = $projects->whereHas($relation, function (Builder $query) use ($relation) {
                    $arr = explode(",", request($relation));
                    $query->whereIn("name", $arr);
                });
            }
        }

        return new ProjectCollection($projects->paginate(10));
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

            $project = Project::create($this->normalizeData($request->validated()));
            AssociationController::associateData($project, "tags", $request);
            AssociationController::associateData($project, "technologies", $request);
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

    private function normalizeData($data)
    {
        if (isset($data["images"]) && !is_string($data["images"])) {
            $data["images"] = json_encode($data["images"]);
        }
        return $data;
    }


    public function like($id)
    {
        try {
            $project = Project::findOrFail($id);
            $userId = auth()->id();
            $project->likers()->toggle($userId);
            $project->refresh();
            return response()->json([
                'message' => 'success',
                'project' => new ProjectResource($project),
            ], 201);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 500;
            if (stringContains($message, "No query results")) {
                $message = 'Project not found';
                $code = 404;
            }
            return response()->json([
                'message' => $message,
            ], $code);
        }
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
            AssociationController::associateData($project, "tags", $request);
            AssociationController::associateData($project, "technologies", $request);
            $project->update($this->normalizeData($request->validated()));
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
