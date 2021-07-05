<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    public $fillable = [
        'owner_id',
        'title',
        'data'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id','id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class,'slide_id','id');
    }
}
