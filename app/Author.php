<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'first_name' => 'string',
        'last_name' => 'string',
    ];
}
