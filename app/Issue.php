<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'name' => 'string',
        'summary' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function arguments()
    {
        return $this->belongsToMany(Argument::class);
    }

    public function facts()
    {
        return $this->belongsToMany(Fact::class);
    }

    public function sources()
    {
        return $this->hasManyThrough(Source::class, Fact::class);
    }
}
