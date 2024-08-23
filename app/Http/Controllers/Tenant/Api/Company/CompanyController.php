<?php

namespace App\Http\Controllers\Tenant\Api\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    public function __invoke()
    {
        return Company::all();
    }
}
