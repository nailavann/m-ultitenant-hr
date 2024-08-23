<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Tenant\TenantRequest;
use App\Models\Tenant;

class TenantController extends Controller
{

    public function index()
    {

        $tenants = Tenant::query()->with('domains')->paginate(10);

        return view('central.tenants.index', compact('tenants'));
    }

    public function createTenant()
    {
        return view('central.tenants.create_tenant');
    }

    public function store(TenantRequest $request)
    {
        try {
            $attributes = collect($request->validated());
            $tenant = Tenant::query()->where('name', $attributes->get('name'))->first();

            if (!$tenant) {
                $tenant = Tenant::query()->create([
                    'name' => $attributes->get('name')
                ]);
            }
            $tenant->domains()->create([
                'domain' => $attributes->get('domainName')
            ]);
            return redirect()->route('central.index.tenant');
        } catch (\Throwable $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }


    }
}
