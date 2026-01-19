<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{

    protected $fillable = [
        'owner_id',
        'code',
        'name',
        'type',
        'age',
        'weight',
    ];
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
