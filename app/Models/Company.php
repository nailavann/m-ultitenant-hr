<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{

    public function users(): HasMany
    {
        return $this->hasMany(UserInformation::class, 'company_id', 'id');
    }
}
