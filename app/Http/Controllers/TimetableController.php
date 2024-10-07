<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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
        $teachers = Teacher::with('user')->get();

        $selectedSubjectId = request('selected_subject_id');
        if ($selectedSubjectId) {
            $teachers = Teacher::where('subject_id', $selectedSubjectId)->with('user')->get();
        }

        $teacher = null;
        if (Auth::user()->hasRole('Teacher')) {
            $teacher = Teacher::where('user_id', Auth::id())->first();
        }

        $periods = range(1, 8);
        $periodsTime = ['8:00 - 8:45', '8:55 - 9:40', '9:50 - 10:35', '10:45 - 11:30', '14:00 - 14:45' , '14:55 - 15:40', '15:50 - 16:35', '16:45 - 17:30'];
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timetable = Timetable::where('class_id', $classId)->get();

        return view('backend.classes.timetable', compact('class', 'periods', 'periodsTime', 'weekDays', 'timetable', 'subjects', 'teachers', 'selectedSubjectId', 'teacher'));
    }


    public function checkAvailability(Request $request, $classId)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'day' => 'required|integer|between:1,5',
            'period' => 'required|integer|between:1,8',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        // Check for teacher conflict
        $conflictEntry = Timetable::where('day', $validated['day'])
            ->where('period', $validated['period'])
            ->where('teacher_id', $validated['teacher_id'])
            ->where('class_id', '!=', $classId)
            ->first();

        if ($conflictEntry) {
            return response()->json(['error' => 'This teacher is already assigned to another class.']);
        }

        // Check if the same day and period are already assigned in this class
        $existingEntry = Timetable::where('day', $validated['day'])
            ->where('period', $validated['period'])
            ->where('class_id', $classId)
            ->first();

        if ($existingEntry) {
            return response()->json(['warning' => 'This day and period are already assigned in this class.']);
        }

        // No conflicts
        return response()->json(['success' => true]);
    }


    public function update(Request $request, $classId)
    {
        $validated = $request->validate([
            'day' => 'required|integer|between:1,5',
            'period' => 'required|integer|between:1,8',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

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
