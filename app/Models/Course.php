<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'content',
        'skill_id',
        'supplier_id',
        'points',
        'is_pre',
    ];

    public function Skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function Supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function Profits()
    {
        return $this->hasMany(Profit::class);
    }

}
