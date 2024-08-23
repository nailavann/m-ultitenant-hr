<?php

namespace App\Http\Controllers\Tenant\Api\Department;

use App\Http\Controllers\Controller;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function __invoke()
    {
        return Department::all();
    }
}
