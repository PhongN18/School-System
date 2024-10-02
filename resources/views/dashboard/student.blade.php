@extends('layouts.app')

@section('content')
    <div class="w-full block mt-8 bg-white">
        <div class="w-full flex px-6 py-16">
            <div class="student-col flex-grow px-2">
                <div class="text-center mb-6">
                    <img src="{{ asset('assets/images/users/' . $student->user->profile_picture) }}" alt="Profile Picture"
                        class="w-40 h-40 rounded-full mx-auto">
                </div>
                <table class="min-w-full table-auto border-collapse">
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Name:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->name }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Email:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->email }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Class:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->class->class_name }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Class room:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->class->class_room }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="student-col flex-grow px-2">
                <table class="min-w-full table-auto border-collapse">
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Phone:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->phone }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Gender:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->gender }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Date of Birth:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->dateofbirth->format('d/m/Y') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Current Address:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->current_address }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Permanent Address:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->user->permanent_address }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Student's Parent:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->parent->user->name }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Parent's Email:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->parent->user->email }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Parent's Phone:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->parent->user->phone }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-500 font-bold">
                            <div class="mb-3">Parent's Address:</div>
                        </td>
                        <td class="text-gray-600 font-bold">
                            <div class="mb-3">{{ $student->parent->user->current_address }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="timetable-grid px-5 mt-6 pb-8">
            <h2 class="font-bold text-center text-xl my-3 pt-3">Timetable</h2>
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-gray-700">Period</th>
                        @foreach ($weekDays as $day)
                            <th class="px-4 py-2 text-gray-700">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periods as $period)
                        @if ($period == 5)
                            <tr>
                                <td colspan="{{ count($weekDays) + 1 }}"
                                    class="text-center text-white font-bold bg-gray-700 px-4 py-2">
                                    Lunch Break
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="border px-4 py-2 text-center">
                                {{ $period }}
                                <p class="text-gray-600 italic text-xs">{{ $periodsTime[$period - 1] }}</p>
                            </td>
                            @foreach ($weekDays as $dayIndex => $day)
                                @php
                                    $dbDay = $dayIndex + 1;
                                    $entry = $class->timetable->firstWhere(function ($t) use ($dbDay, $period) {
                                        return $t->day == $dbDay && $t->period == $period;
                                    });
                                @endphp
                                <td class="border px-4 py-2">
                                    @if ($entry)
                                        <strong>{{ $entry->subject->name }}</strong><br>
                                        <small>Teacher: {{ $entry->teacher->user->name }}</small>
                                    @else
                                        <span class="text-gray-400">Unassigned</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
