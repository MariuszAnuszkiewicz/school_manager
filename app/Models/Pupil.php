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
}
