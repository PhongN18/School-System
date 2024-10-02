<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Parents;
use App\Models\Classes;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classes::withCount('students')->latest()->paginate(10);

        return view('backend.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::latest()->get();

        return view('backend.classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_name'        => 'required|string|max:255|unique:classes',
            'class_numeric'     => 'required|numeric',
            'teacher_id'        => 'required|numeric',
            'class_description' => 'required|string|max:255'
        ]);

        Classes::create([
            'class_name'        => $request->class_name,
            'class_numeric'     => $request->class_numeric,
            'teacher_id'        => $request->teacher_id,
            'class_description' => $request->class_description
        ]);

        return redirect()->route('classes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $class = Classes::with(['teacher', 'students.user'])->findOrFail($id);
        $parent = Parents::where('user_id', Auth::id())->first();
        $childrenInfo = null;
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
            }

        $sortedStudents = $class->students->sortBy(function ($student) {
            return $student->user->name;
        });

        return view('backend.classes.show', compact('class', 'sortedStudents', 'childrenInfo'));
    }

    public function teacherClasses(Teacher $teacher)
    {
        $classes = Classes::where('teacher_id', $teacher->id)->withCount('students')->get();

        return view('backend.classes.teacher-classes', compact('classes', 'teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teachers = Teacher::latest()->get();
        $class = Classes::findOrFail($id);

        return view('backend.classes.edit', compact('class','teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'class_name'        => 'required|string|max:255|unique:classes,class_name,'.$id,
            'class_numeric'     => 'required|numeric',
            'teacher_id'        => 'required|numeric',
            'class_description' => 'required|string|max:255',
            'class_room'        => 'required|string|max:255'
        ]);

        $class = Classes::findOrFail($id);

        $class->update([
            'class_name'        => $request->class_name,
            'class_numeric'     => $request->class_numeric,
            'teacher_id'        => $request->teacher_id,
            'class_description' => $request->class_description,
            'class_room'        => $request->class_room
        ]);

        return redirect()->route('classes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = Classes::findOrFail($id);

        $class->subjects()->detach();
        $class->delete();

        return back();
    }
}
