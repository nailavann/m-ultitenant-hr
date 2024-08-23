<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = [
            'Yönetici',
            'İnsan Kaynakları',
            'Yazılım Geliştirici',
        ];

        foreach ($department as $item) {
            Department::query()->create([
                'name' => $item,
            ]);
        }
    }
}
