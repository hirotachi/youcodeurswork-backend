<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProjectCollection;

class UserController extends Controller
{
    public function myProjects()
    {
        return new ProjectCollection(auth()->user()->projects);
    }

    public function updateProfile(ProfileRequest $request)
    {

        auth()->user()->update($request->validated());
        return response()->json(['message' => 'Profile updated successfully']);
    }
}
