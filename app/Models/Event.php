<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'date',
        'start',
        'end',
    ];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }
}
