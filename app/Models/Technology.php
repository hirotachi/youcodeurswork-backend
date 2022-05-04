<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $fillable = ['name'];

    /**
     * Get all projects that use this technology.
     */
    public function projects()
    {
        return $this->morphedByMany(Project::class, "technologyable");
    }
}
