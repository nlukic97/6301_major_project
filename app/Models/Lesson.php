<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    public $fillable = [
        'teacher_id',
        'slide_id',
        'uuid',
        'in_progress'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class,'teacher_id','id');
    }
}
