<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mews\Purifier\Casts\CleanHtml;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'location', 'image', 'type', 'user_id', 'company_name', 'company_site', 'apply_by',
        'company_logo', 'remote'
    ];

    protected $hidden = [
        'user_id'
    ];
    protected $casts = [
        "description" => CleanHtml::class,
        "remote" => "boolean"
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
}
