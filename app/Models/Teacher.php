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
        'gender',
        'phone',
        'dateofbirth',
        'current_address',
        'permanent_address',
        'subject_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function students()
    {
        return $this->classes()->withCount('students');
    }
}
