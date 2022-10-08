<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'name' => 'Course 1',
            'content' => 'Course content 1',
            'skill_id' => 1,
            'supplier_id' => 1,
            'points' => 10,
        ]);
    }
}
