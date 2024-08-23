<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $guarded = [];

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset($value) : null
        );
    }
}
