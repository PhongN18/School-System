@extends('layouts.app')

@section('content')
    <div class="container mt-8">
        <div>
            <h1 class="mb-4 font-bold text-2xl text-gray-700">Class Details</h1>

            <div class="w-full flex">
                <div class="w-1/3"><strong>Class Name:</strong> {{ $class->class_name }}</div>
                <div class="w-1/3"><strong>Teacher Name:</strong> {{ $class->teacher->user->name ?? 'N/A' }}</div>
                <div class="w-1/3"><strong>Class Room:</strong> {{ $class->class_room }}</div>
            </div>

            @role('Admin')
                <div class="mt-4">
                    <a href="{{ route('timetable.show', $class->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                        View Timetable
                    </a>
                </div>
            @endrole
        </div>

        <h2 class="mt-8 mb-4">Students List</h2>

        <div class="bg-white rounded border-b-4 border-gray-300">
            <div
                class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-600 text-white rounded-tl rounded-tr">
                <div class="w-1/12 px-4 py-3">#</div>
                <div class="w-3/12 px-4 py-3">Name</div>
                <div class="w-2/12 px-4 py-3">Gender</div>
                <div class="w-3/12 px-4 py-3">DOB</div>
                <div class="w-3/12 px-4 py-3">Email</div>
            </div>

            @foreach ($sortedStudents as $student)
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600">
                        {{ $student->id }}
                    </div>
                    <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600">
                        {{ $student->user->name }}
                    </div>
                    <div class="w-2/12 px-4 py-3 text-sm font-semibold text-gray-600">
                        {{ ucfirst($student->user->gender) }}
                    </div>
                    <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600">
                        {{ $student->user->dateofbirth->format('Y-m-d') }}
                    </div>
                    <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600">
                        {{ $student->user->email }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
