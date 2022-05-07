<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    use CommonTrait;

    static public string $role = 'recruiter';


    /**
     * Display a listing of the resource.
     *
     * @return JobCollection
     */
    public function index()
    {
        $jobs = Job::query()->orderByDesc('created_at');
        $AndFilters = ["type", "category", "remote"];
        foreach ($AndFilters as $filter) {
            $val = request()->query($filter);
            if ($val) {
                if ($val === "true") {
                    $val = true;
                } elseif ($val === "false") {
                    $val = false;
                }

                $jobs = $jobs->where($filter, $val);
            }
        }
        if (request()->query("location")) {
            $location = request()->query("location");
            $jobs = $jobs->where("location", "like", "%$location%");
        }
        if (request()->query("q")) {
            $jobs = $jobs->where(function (Builder $query) {
                $q = request()->query("q");
                $orFilters = ["company_name", "title"];
                foreach ($orFilters as $filter) {
                    $query->orWhere($filter, "LIKE", "%$q%");
                }
            });
        }

        $relations = ["tags", "technologies"];
        foreach ($relations as $relation) {
            if (request()->query($relation)) {
                $jobs = $jobs->whereHas($relation, function (Builder $query) use ($relation) {
                    $arr = explode(",", request()->query($relation));
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
            $this->associateData($job, "tags", $request);
            $this->associateData($job, "technologies", $request);
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
            $this->associateData($job, "tags", $request);
            $this->associateData($job, "technologies", $request);
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

    private function model(): Model
    {
        return new Job();
    }
}
