<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['name'];

    public function pupils()
    {
        return $this->belongsToMany(Pupil::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function ratings()
    {
        return $this->belongsToMany(Rating::class)->withTimestamps();
    }
}
