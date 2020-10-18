<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    use HasFactory;
    public $table = 'lessons_plans';
    public $timestamps = true;
    protected $fillable = [
        'class_in_school_id',
        'hours',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday'
    ];
}
