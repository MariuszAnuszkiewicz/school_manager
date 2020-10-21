<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'title',
        'day',
        'hour_start',
        'hour_end'
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }
}
