@extends('layouts.app')

@section('content')
    <div class="mt-8 bg-white rounded">
        <div class="w-full flex px-6 py-16">
            <div class="teacher-col flex-grow px-2">
                <div class="text-center mb-6">
                    <img src="{{ asset('assets/images/users/' . $teacher->user->profile_picture) }}" alt="Profile Picture"
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
                            <div class="mb-3 capitalize">{{ $teacher->user->gender }}</div>
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
        <div class="timetable-grid px-5 mt-6 pb-8">
            <h2 class="font-bold text-center text-xl my-3 pt-3">Teaching Schedule</h2>
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-gray-700 w-2/12">Period</th>
                        @foreach ($weekDays as $day)
                            <th class="px-4 py-2 text-gray-700 w-2/12">{{ $day }}</th>
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
                            <td class="border px-4 py-2 text-center w-2/12">
                                {{ $period }}
                                <p class="text-gray-600 italic text-xs">{{ $periodsTime[$period - 1] }}</p>
                            </td>
                            @foreach ($weekDays as $dayIndex => $day)
                                @php
                                    $dbDay = $dayIndex + 1;
                                    $entry = $teachingSchedule->firstWhere(function ($t) use ($dbDay, $period) {
                                        return $t->day == $dbDay && $t->period == $period;
                                    });
                                @endphp
                                <td class="border px-4 py-2 w-2/12">
                                    @if ($entry)
                                        <strong>{{ $entry->class->class_name }}</strong><br>
                                        <small>Room: {{ $entry->class->class_room }}</small><br>
                                        <small>{{ $entry->subject->name }}</small>
                                    @else
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
