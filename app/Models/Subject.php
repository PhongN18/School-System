<?php

namespace App\Models;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject_code',

        'description'
    ];

    public function teacher()
    {
        return $this->hasMany(Teacher::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
