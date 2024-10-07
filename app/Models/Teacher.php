<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function timetable()
    {
        return $this->hasMany(Timetable::class, 'teacher_id');
    }

    public function student()
    {
        return $this->classes()->withCount('students');
    }

    public function class()
    {
        return $this->hasMany(Classes::class, 'teacher_id');
    }
}
