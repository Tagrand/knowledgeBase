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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sources() {
        return $this->belongsToMany(Source::class);
    }
}
