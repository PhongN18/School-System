<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Classes;
use App\Models\Student;
use App\Models\Parents;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('class')->latest()->paginate(10);

        return view('backend.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = Classes::latest()->get();
        $parents = Parents::with('user')->latest()->get();

        return view('backend.students.create', compact('classes','parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8',
            'parent_id'         => 'required|numeric',//nullable
            'class_id'          => 'required|numeric',
            'roll_number'       => [
                'required',
                'numeric',
                Rule::unique('students')->where(function ($query) use ($request) {
                    return $query->where('class_id', $request->class_id);
                })
            ],
            'gender'            => 'required|string',
            'phone'             => 'required|string|max:255',
            'dateofbirth'       => 'required|date',
            'current_address'   => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255'
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'gender'            => $request->gender,
            'phone'             => $request->phone,
            'dateofbirth'       => $request->dateofbirth,
            'current_address'   => $request->current_address,
            'permanent_address' => $request->permanent_address
        ]);

        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($user->name).'-'.$user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = 'avatar.png';
        }
        $user->update([
            'profile_picture' => $profile
        ]);

        $user->student()->create([
            'parent_id'         => $request->parent_id,
            'class_id'          => $request->class_id,
            'roll_number'       => $request->roll_number
        ]);

        $user->assignRole('Student');

        return redirect()->route('student.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $class = Classes::where('id', $student->class_id)->first();
        $parent = Parents::where('user_id', Auth::id())->first();
        $periods = range(1, 8);
        $periodsTime = ['8:00 - 8:45', '8:55 - 9:40', '9:50 - 10:35', '10:45 - 11:30', '14:00 - 14:45' , '14:55 - 15:40', '15:50 - 16:35', '16:45 - 17:30'];
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $childrenInfo = null;
        if ($parent) {
            $children = Student::where('parent_id', $parent->id)
            ->with(['class.teacher.user'])
            ->get();

            foreach ($children as $child) {
                $child->user->classname = $child->class->class_name;
                $child->user->teacher_name = $child->class->teacher->user->name;
                $child->user->student_id = $child->id;
            }
            $childrenInfo = $children->pluck('user');
        }
        return view('backend.students.show', compact('class','student', 'childrenInfo', 'periods', 'periodsTime', 'weekDays'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classes = Classes::latest()->get();
        $parents = Parents::with('user')->latest()->get();

        return view('backend.students.edit', compact('classes','parents','student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users,email,'.$student->user_id,
            'parent_id'         => 'required|numeric',
            'class_id'          => 'required|numeric',
            'roll_number'       => [
                'required',
                'numeric',
                Rule::unique('students')->ignore($student->id)->where(function ($query) use ($request) {
                    return $query->where('class_id', $request->class_id);
                })
            ],
            'gender'            => 'required|string',
            'phone'             => 'required|string|max:255',
            'dateofbirth'       => 'required|date',
            'current_address'   => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255'
        ]);

        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($student->user->name).'-'.$student->user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = $student->user->profile_picture;
        }

        $student->user()->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'profile_picture'   => $profile
        ]);

        $student->update([
            'parent_id'         => $request->parent_id,
            'class_id'          => $request->class_id,
            'roll_number'       => $request->roll_number,
            'gender'            => $request->gender,
            'phone'             => $request->phone,
            'dateofbirth'       => $request->dateofbirth,
            'current_address'   => $request->current_address,
            'permanent_address' => $request->permanent_address
        ]);

        return redirect()->route('student.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $user = User::findOrFail($student->user_id);
        $user->student()->delete();
        $user->removeRole('Student');

        if ($user->delete()) {
            if($user->profile_picture != 'avatar.png') {
                $image_path = public_path() . '/images/profile/' . $user->profile_picture;
                if (is_file($image_path) && file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }

        return back();
    }
}
