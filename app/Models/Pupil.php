<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pupil extends Model
{
    use HasFactory;

    public $timestamp = true;
    protected $fillable = ['user_id', 'class_in_school_id'];

    public function classInSchool()
    {
        return $this->belongsTo(ClassInSchool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function ratings()
    {
        return $this->belongsToMany(Rating::class)->withPivot('semester')->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class);
    }

}
