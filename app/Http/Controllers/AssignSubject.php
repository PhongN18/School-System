<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;

class AssignSubject extends Controller
{
    public function assignSubject($classid)
    {
        $subjects = Subject::latest()->get();
        $assigned = Grade::with(['subjects', 'students'])->findOrFail($classid);

        return view('backend.classes.assign-subject', compact('classid', 'subjects', 'assigned'));
    }
}
