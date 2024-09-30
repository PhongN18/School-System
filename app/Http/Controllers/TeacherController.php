<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('user')->latest()->paginate(10);

        return view('backend.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::latest()->get();
        return view('backend.teachers.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8',
            'gender'            => 'required|string',
            'phone'             => 'nullable|string|max:255',
            'dateofbirth'       => 'nullable|date',
            'current_address'   => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'subject_id'        => 'required|exists:subjects,id',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Create the user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'gender' => $request->gender,
            'phone' => $request->phone,
            'dateofbirth' => $request->dateofbirth,
            'current_address' => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'profile_picture' => $request->profile_picture
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($user->name).'-'.$user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = 'avatar.png';
        }

        // Update profile picture field in user model
        $user->update([
            'profile_picture' => $profile
        ]);

        // Create associated teacher record
        $user->teacher()->create([
            'subject_id'        => $request->subject_id
        ]);

        // Assign role
        $user->assignRole('Teacher');

        return redirect()->route('teacher.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $teacher = Teacher::with('user')->findOrFail($teacher->id);
        $subjects = Subject::latest()->get();
        return view('backend.teachers.edit', compact('teacher','subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        // Validate input
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users,email,'.$teacher->user_id,
            'gender'            => 'required|string',
            'phone'             => 'nullable|string|max:255',
            'dateofbirth'       => 'nullable|date',
            'current_address'   => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Find the user related to the teacher
        $user = User::findOrFail($teacher->user_id);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profile = Str::slug($user->name).'-'.$user->id.'.'.$request->profile_picture->getClientOriginalExtension();
            $request->profile_picture->move(public_path('images/profile'), $profile);
        } else {
            $profile = $user->profile_picture;
        }

        // Update user information
        $user->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'profile_picture'   => $profile
        ]);

        // Update associated teacher record
        $user->teacher()->update([
            'gender'            => $request->gender,
            'phone'             => $request->phone,
            'dateofbirth'       => $request->dateofbirth,
            'current_address'   => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'subject_id'        => $request->subject_id
        ]);

        return redirect()->route('teacher.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $user = User::findOrFail($teacher->user_id);

        // Delete associated teacher record
        $user->teacher()->delete();

        // Remove teacher role
        $user->removeRole('Teacher');

        // Handle profile picture deletion (if not the default one)
        if ($user->profile_picture != 'avatar.png') {
            $image_path = public_path() . '/images/profile/' . $user->profile_picture;
            if (is_file($image_path) && file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Finally, delete the user
        $user->delete();

        return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully.');
    }

}
