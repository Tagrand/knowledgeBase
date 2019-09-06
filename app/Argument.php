<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Argument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'id' => 'int',
        'reason' => 'string',
        'source_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function facts() {
        return $this->belongsToMany(Fact::class)->withPivotValue('is_supportive', true);
    }

    public function issues() {
        return $this->belongsToMany(Issue::class);
    }

    public function source() {
        return $this->belongsTo(Source::class);
    }
}
