<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [

        ];
        foreach ($companies as $company) {
            Company::query()->create([
                'name' => $company['name'],
                'location' => $company['location']
            ]);
        }
    }
}
