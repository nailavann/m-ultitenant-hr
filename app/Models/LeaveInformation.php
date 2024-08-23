<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveInformation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function getRemainingDaysAttribute()
    {
        return $this->entitlement - $this->used_days;
    }

}
