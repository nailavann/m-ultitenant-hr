<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emergency extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->name . ' ' . $this->surname,
        );
    }
}
