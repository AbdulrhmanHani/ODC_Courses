<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function Courses()
    {
        return $this->hasMany(Course::class);
    }

    public function Students()
    {
        return $this->hasMany(Student::class);
    }

}
