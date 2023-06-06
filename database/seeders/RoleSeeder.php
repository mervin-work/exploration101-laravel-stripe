<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    protected $array = [
        ['title' => 'Super Admin'],
        ['title' => 'Admin'],
        ['title' => 'Staff'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->array as $value) {
            \App\Models\Role::create($value);
        }
    }
}
