<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'claim' => 'string',
        'person' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function arguments() {
        return $this->belongsToMany(Argument::class);
    }

    public function issues() {
        return $this->belongsToMany(Issue::class);
    }
}
