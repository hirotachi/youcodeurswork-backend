<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = ['name'];

    /**
     * Get all projects that are tagged with this tag.
     */
    public function projects()
    {
        return $this->morphedByMany(Project::class, 'taggable');
    }

    /**
     * Get all jobs that are tagged with this tag.
     */
    public function jobs()
    {
        return $this->morphedByMany(Job::class, 'taggable');
    }
}
