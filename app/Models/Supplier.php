<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amount',
        'recived',
    ];

    public function Courses()
    {
        return $this->hasMany(Course::class);
    }
}
