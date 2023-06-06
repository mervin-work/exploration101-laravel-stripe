<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    protected $array = [
        ['title' => 'user_create'],
        ['title' => 'user_edit'],
        ['title' => 'user_show'],
        ['title' => 'user_delete'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->array as $value) {
            \App\Models\Permission::create($value);
        }
    }
}
