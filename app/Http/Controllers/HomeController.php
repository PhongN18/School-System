<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Timetable;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Admin')) {
            $parents = Parents::latest()->get()->count();
            $teachers = Teacher::latest()->get()->count();
            $students = Student::latest()->get()->count();
            $subjects = Subject::latest()->get()->count();
            $classes = Classes::latest()->get()->count();
            return view('home',compact( 'parents', 'teachers', 'students', 'subjects', 'classes'));
        } elseif($user->hasRole('Parent')) {
            $parent = Parents::where('user_id', $user->id)->first();

            if ($parent) {
                $children = Student::where('parent_id', $parent->id)
                                    ->with(['class.teacher.user'])
                                    ->get();

                foreach ($children as $student) {
                    $student->user->classname = $student->class->class_name;
                    $student->user->teacher_name = $student->class->teacher->user->name;
                    $student->user->student_id = $student->id;
                }

                $childrenInfo = $children->pluck('user');

                return view('home', compact('childrenInfo'));
            }
        } elseif ($user->hasRole('Student')) {
            $student = Student::where('user_id', $user->id)->with('class.timetable.subject', 'class.timetable.teacher.user', 'user', 'parent.user')->first();

            if ($student) {
                $class = $student->class;
                $periods = range(1, 8);
                $periodsTime = ['8:00 - 8:45', '8:55 - 9:40', '9:50 - 10:35', '10:45 - 11:30', '14:00 - 14:45' , '14:55 - 15:40', '15:50 - 16:35', '16:45 - 17:30'];
                $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

                return view('dashboard.student', compact('student', 'class', 'weekDays', 'periods', 'periodsTime'));
            }

            return view('dashboard.student')->with('error', 'No class or timetable available.');
        } elseif ($user->hasRole('Teacher')) {
            $teacher = Teacher::where('user_id', $user->id)->with('subject', 'class')->first();

            if ($teacher) {
                $teachingSchedule = Timetable::where('teacher_id', $teacher->id)
                    ->with('class', 'subject')
                    ->orderBy('day')
                    ->orderBy('period')
                    ->get();

                    $periods = range(1, 8);
                    $periodsTime = ['8:00 - 8:45', '8:55 - 9:40', '9:50 - 10:35', '10:45 - 11:30', '14:00 - 14:45' , '14:55 - 15:40', '15:50 - 16:35', '16:45 - 17:30'];
                    $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

                return view('dashboard.teacher', compact('teacher', 'teachingSchedule', 'weekDays', 'periods', 'periodsTime'));
            }
        return view('home');
    }
}

    public function changePassword(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
