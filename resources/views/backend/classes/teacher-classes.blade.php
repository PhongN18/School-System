@extends('layouts.app')

@section('content')
    <div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Classes</h2>
            </div>
        </div>

        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
            <div
                class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-600 text-white rounded-tl rounded-tr">
                <div class="w-1/12 px-4 py-3">#</div>
                <div class="w-3/12 px-4 py-3">Name</div>
                <div class="w-2/12 px-4 py-3 text-center">No of Students</div>
                <div class="w-2/12 px-4 py-3 text-center">Teacher</div>
                <div class="w-2/12 px-4 py-3 text-center">Class Room</div>
                <div class="w-2/12 px-4 py-3 text-right"></div>
            </div>
            @foreach ($classes as $class)
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-1/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">
                        {{ $class->class_numeric }}</div>
                    <div class="w-3/12 px-4 py-3 text-sm font-semibold text-gray-600 tracking-tight">
                        {{ $class->class_name }}</div>
                    <div class="w-2/12 px-4 py-3 text-sm text-center font-semibold text-gray-600 tracking-tight">
                        <span class="bg-gray-200 text-sm mr-1 mb-1 px-2 font-semibold border rounded-full">
                            {{ $class->students_count }}
                        </span>
                    </div>
                    <div class="w-2/12 px-4 py-3 text-sm text-center font-semibold text-gray-600 tracking-tight">
                        {{ $class->teacher->user->name ?? '' }}</div>
                    <div class="w-2/12 px-4 py-3 text-sm text-center font-semibold text-gray-600 tracking-tight">
                        {{ $class->class_room }}</div>
                    <div class="w-2/12 flex items-center justify-end px-3">
                        <a class="block py-2 px-4 text-sm bg-gray-300 rounded-full hover:bg-gray-500 hover:text-white"
                            href="{{ route('timetable.show', $class->id) }}">View
                            Timetable</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
