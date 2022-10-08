<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'token',
        'points',
        'skill_id',
        'skill_status',
        'gender',
    ];

    public function Skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function Profits()
    {
        return $this->belongsToMany(Profit::class);
    }
}
