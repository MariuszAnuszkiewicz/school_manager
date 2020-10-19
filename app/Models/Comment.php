<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'teacher_id',
        'comment',
    ];

    public function pupils()
    {
        return $this->belongsToMany(Pupil::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
