<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Grade::withCount('students')->latest()->paginate(10);

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
            'class_name'        => 'required|string|max:255|unique:grades',
            'class_numeric'     => 'required|numeric',
            'teacher_id'        => 'required|numeric',
            'class_description' => 'required|string|max:255'
        ]);

        Grade::create([
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teachers = Teacher::latest()->get();
        $class = Grade::findOrFail($id);

        return view('backend.classes.edit', compact('class','teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'class_name'        => 'required|string|max:255|unique:grades,class_name,'.$id,
            'class_numeric'     => 'required|numeric',
            'teacher_id'        => 'required|numeric',
            'class_description' => 'required|string|max:255'
        ]);

        $class = Grade::findOrFail($id);

        $class->update([
            'class_name'        => $request->class_name,
            'class_numeric'     => $request->class_numeric,
            'teacher_id'        => $request->teacher_id,
            'class_description' => $request->class_description
        ]);

        return redirect()->route('classes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = Grade::findOrFail($id);

        $class->subjects()->detach();
        $class->delete();

        return back();
    }
    public function assignSubject($classid)
    {
        $subjects = Subject::latest()->get();
        $assigned = Grade::with(['subjects', 'students'])->findOrFail($classid);

        return view('backend.classes.assign-subject', compact('classid', 'subjects', 'assigned'));
    }
    public function storeAssignedSubject(Request $request, $id)
    {
        $class = Grade::findOrFail($id);

        $class->subjects()->sync($request->selectedsubjects);

        return redirect()->route('classes.index');
    }
}
