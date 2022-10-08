<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'student_id',
        'profit',
    ];

    public function Student()
    {
        return $this->belongsToMany(Student::class);
    }

    public function Course()
    {
        return $this->belongsTo(Course::class);
    }

}
