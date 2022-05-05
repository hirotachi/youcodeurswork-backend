<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobResource;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TechnologyCollection;
use App\Models\Job;
use App\Models\Tag;
use App\Models\Technology;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    static public string $role = 'recruiter';

    /**
     * Display a listing of the resource.
     *
     * @return JobCollection
     */
    public function index()
    {
        $query = request("q");
        $jobs = Job::where("title", "LIKE", "%$query%");
        $filters = ["company_name", "location"];
        foreach ($filters as $filter) {
            $jobs = $jobs->orWhere($filter, "LIKE", "%$query%");
        }
        $relations = ["tags", "technologies"];
        foreach ($relations as $relation) {
            if (request()->has($relation) && request($relation) != "") {
                $jobs = $jobs->whereHas($relation, function (Builder $query) use ($relation) {
                    $arr = explode(",", request($relation));
                    $query->whereIn("name", $arr);
                });
            }
        }

        return new JobCollection($jobs->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreJobRequest $request)
    {
        try {
            DB::beginTransaction();
            $job = Job::create($request->validated());
            AssociationController::associateData($job, "tags", $request);
            AssociationController::associateData($job, "technologies", $request);
            DB::commit();
            return response()->json([
                'message' => 'success',
                'job' => new JobResource($job),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            return new JobResource(Job::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJobRequest  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateJobRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            try {
                $job = Job::findOrFail($id);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Job not found',
                ], 404);
            }
            $this->authorize('update', $job);
            AssociationController::associateData($job, "tags", $request);
            AssociationController::associateData($job, "technologies", $request);
            $job->update($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'success',
                'job' => new JobResource($job),
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
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            try {
                $job = Job::firstOrFail($id);
                $job->technologies()->detach();
                $job->tags()->detach();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Job not found',
                ], 404);
            }
            $this->authorize('delete', $job);
            $job->delete();
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

    public function tags()
    {
        return new TagCollection(Tag::has('jobs')->get());
    }

    public function technologies()
    {
        return new TechnologyCollection(Technology::has('jobs')->get());
    }
}
