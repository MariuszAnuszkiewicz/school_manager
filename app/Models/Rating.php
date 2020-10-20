<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = ['rating'];

    public function pupils()
    {
        return $this->belongsToMany(Pupil::class);
    }
}
