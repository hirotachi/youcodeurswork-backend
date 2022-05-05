<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\JobCollection;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function myProjects()
    {
        return new ProjectCollection(auth()->user()->projects()->paginate(10));
    }

    public function myJobs()
    {
        return new JobCollection(auth()->user()->jobs()->paginate(10));
    }

    public function jobs($id)
    {
        try {
            return new JobCollection(User::findOrFail($id)->jobs()->paginate(10));
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function projects($id)
    {
        try {
            return new ProjectCollection(User::findOrFail($id)->projects()->paginate(10));
        } catch (\Exception $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function show($id)
    {
        try {
            return new UserResource(User::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

    public function updateProfile(ProfileRequest $request)
    {

        auth()->user()->update($request->validated());
        return response()->json(['message' => 'Profile updated successfully']);
    }
}
