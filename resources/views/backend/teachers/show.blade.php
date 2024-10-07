@extends('layouts.app')

@section('content')
    <div class="mt-8 bg-white rounded">
        <div class="w-full flex px-6 py-16">
            <div class="teacher-col flex-grow px-2">
                <div class="text-center mb-6">
                    <img src="{{ asset('images/profile/' . $teacher->user->profile_picture) }}" alt="Profile Picture"
                        class="w-40 h-40 rounded-full mx-auto">
                </div>
                <table class="min-w-full table-auto border-collapse">
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Name:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->name }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Email:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->email }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="teacher-col flex-grow px-2">
                <table class="min-w-full table-auto border-collapse">
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Phone:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->phone }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Gender:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->gender }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Date of Birth:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->dateofbirth->format('d/m/Y') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Current Address:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->current_address }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Permanent Address:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $teacher->user->permanent_address }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="w-full px-6 pb-10">
            <h2 class="font-bold text-center text-xl my-3 text-gray-700">Teaching Schedule</h2>
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="w-1/8 px-4 py-2 text-left text-gray-500 font-bold">Day</th>
                        <th class="w-1/4 px-4 py-2 text-left text-gray-500 font-bold">Period</th>
                        <th class="w-1/4 px-4 py-2 text-left text-gray-500 font-bold">Class</th>
                        <th class="w-1/8 px-4 py-2 text-left text-gray-500 font-bold">Class Room</th>
                        <th class="w-1/4 px-4 py-2 text-left text-gray-500 font-bold">Subject</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachingSchedule as $schedule)
                        <tr>
                            <td class="border w-1/8 px-4 py-2 text-gray-600">{{ $days[$schedule->day] }}</td>
                            <td class="border w-1/4 px-4 py-2 text-gray-600">
                                Period {{ $schedule->period }} ({{ $periodTimes[$schedule->period] }})
                            </td>
                            <td class="border w-1/4 px-4 py-2 text-gray-600">{{ $schedule->class->class_name }}</td>
                            <td class="border w-1/8 px-4 py-2 text-gray-600">{{ $schedule->class->class_room }}</td>
                            <td class="border w-1/4 px-4 py-2 text-gray-600">{{ $schedule->subject->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-10">
            <h2 class="font-bold text-center text-xl my-3 text-gray-700">Classes</h2>
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
    </div>
@endsection
