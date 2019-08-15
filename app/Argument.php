<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Argument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'reason' => 'string',
        'person' => 'string',
        'source' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function facts() {
        return $this->belongsToMany(Fact::class);
    }

    public function issues() {
        return $this->belongsToMany(Issue::class);
    }
}
