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
        'source' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
