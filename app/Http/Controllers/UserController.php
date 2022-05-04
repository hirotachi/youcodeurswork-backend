<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectCollection;

class UserController extends Controller
{
    public function myProjects()
    {
        return new ProjectCollection(auth()->user()->projects);
    }
}
