<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'name' => 'string',
    ];
}
