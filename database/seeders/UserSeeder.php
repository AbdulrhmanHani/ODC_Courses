<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'super_admin',
            'password' => bcrypt('password'),
        ]);
        User::create([
            'name' => 'admin2',
            'email' => 'admin2@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
    }
}
