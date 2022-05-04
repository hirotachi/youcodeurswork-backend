<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mews\Purifier\Casts\CleanHtml;

class Project extends Model
{

    protected $fillable = [
        'name', 'description', 'images', 'repo_link', 'user_id'
    ];

    protected $casts = [
        "description" => CleanHtml::class,
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the tags  for the project.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all the technologies  for the project.
     */
    public function technologies()
    {
        return $this->morphToMany(Technology::class, 'technologyable');
    }

    /**
     * Get All likes for the project.
     */
    public function likers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }
}
