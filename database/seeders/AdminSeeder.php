<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'username' => 'ayah',
            'password' => bcrypt('ayah123'),
        ]);

        Admin::create([
            'username' => 'alvin',
            'password' => bcrypt('alvin123'),
        ]);

        Admin::create([
            'username' => 'mama',
            'password' => bcrypt('mama123'),
        ]);
    }
}
