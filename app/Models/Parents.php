<?php

namespace App\Models;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'gender',
        'phone',
        'current_address',
        'permanent_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
