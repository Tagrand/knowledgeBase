<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'name' => 'string',
    ];

    public function facts() {
        return $this->hasMany(Fact::class);
    }

    public function arguments() {
        return $this->hasMany(Argument::class);
    }

    public function authors() {
        return $this->belongsToMany(Author::class);
    }
}
