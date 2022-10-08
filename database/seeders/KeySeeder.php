<?php

namespace Database\Seeders;

use App\Models\Key;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);
        Key::create([
            'key' => Str::random(64),
        ]);

    }

}
