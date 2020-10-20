<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassInSchool extends Model
{
    use HasFactory;
    public $table = 'class_in_school';
    public $timestamps = false;
    protected $fillable = ['name'];


    public function lessonPlan()
    {
        return $this->hasOne(LessonPlan::class);

    public function pupil()
    {
        return $this->hasOne(Pupil::class);
    }
}
