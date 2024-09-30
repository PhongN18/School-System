<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Timetable;

class TimetableController extends Controller
{
    public function show($classId)
{
    $class = Classes::with(['students', 'teacher.user'])->findOrFail($classId);
    $subjects = Subject::all();
    $teachers = Teacher::with('user')->get(); // Get all teachers initially

    // If a subject is selected, get the teachers who teach this subject
    $selectedSubjectId = request('selected_subject_id');
    if ($selectedSubjectId) {
        $teachers = Teacher::where('subject_id', $selectedSubjectId)->with('user')->get();
    }

    $periods = range(1, 8);
    $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $timetable = Timetable::where('class_id', $classId)->get();

    return view('backend.classes.timetable', compact('class', 'periods', 'weekDays', 'timetable', 'subjects', 'teachers', 'selectedSubjectId'));
}


public function update(Request $request, $classId)
{
    // Validate the incoming request
    $validated = $request->validate([
        'day' => 'required|integer|between:1,5',
        'period' => 'required|integer|between:1,8',
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'nullable|exists:teachers,id',
    ]);

    // Update or create the timetable entry
    Timetable::updateOrCreate(
        [
            'class_id' => $classId,
            'day' => $validated['day'],
            'period' => $validated['period'],
        ],
        [
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
        ]
    );

    return redirect()->back()->with('success', 'Timetable updated successfully');
}


        public function getTeachers($subjectId)
    {
        $teachers = Teacher::where('subject_id', $subjectId)->with('user')->get();

        $teachersData = $teachers->map(function($teacher) {
            return [
                'id' => $teacher->id,
                'user_id' => $teacher->user->id,
                'name' => $teacher->user->name
            ];
        });

        return response()->json(['teachers' => $teachersData]);
    }


    public function destroy($classId)
    {
        Timetable::where('class_id', $classId)->delete();
        return redirect()->back()->with('success', 'Timetable deleted successfully');
    }
}
