<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    protected $array = [
        [
            'role_id' => 1,
            'permission_id' => 1
        ],
        [
            'role_id' => 1,
            'permission_id' => 2
        ],
        [
            'role_id' => 1,
            'permission_id' => 3
        ],
        [
            'role_id' => 1,
            'permission_id' => 4
        ],
        [
            'role_id' => 2,
            'permission_id' => 1
        ],
        [
            'role_id' => 2,
            'permission_id' => 2
        ],
        [
            'role_id' => 2,
            'permission_id' => 3
        ],
        [
            'role_id' => 3,
            'permission_id' => 1
        ],
        [
            'role_id' => 3,
            'permission_id' => 3
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->array as $value) {
            \App\Models\RolePermission::create($value);
        }
    }
}
