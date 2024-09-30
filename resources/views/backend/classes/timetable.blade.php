@extends('layouts.app')

@section('content')
    <div class="container mt-8">
        <h1 class="mb-4 font-bold">Timetable for {{ $class->class_name }}</h1>

        <!-- Timetable Grid -->
        <div class="timetable-grid">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Period</th>
                        @foreach ($weekDays as $day)
                            <th class="px-4 py-2">{{ $day }}</th>
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
                            <td class="border px-4 py-2 text-center">{{ $period }}</td>
                            @foreach ($weekDays as $dayIndex => $day)
                                @php
                                    $dbDay = $dayIndex + 1;
                                    $entry = $timetable->firstWhere(function ($t) use ($dbDay, $period) {
                                        return $t->day == $dbDay && $t->period == $period;
                                    });
                                @endphp
                                <td class="border px-4 py-2">
                                    @if ($entry)
                                        <strong>{{ $entry->subject->name }}</strong><br>
                                        <small>{{ $entry->teacher->user->name }}</small>
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

        <!-- Form for Adding/Updating Info -->
        <form action="{{ route('timetable.update', $class->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mt-8">
                <h2 class="text-xl font-bold">Edit Timetable Entry</h2>
                <div class="flex justify-between">
                    <div class="mb-4">
                        <label for="day">Day: </label>
                        <select name="day" id="dayInput" class="border rounded px-2 py-1">
                            <option value="">-- Select Day --</option>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="period">Period: </label>
                        <select name="period" id="periodInput" class="border rounded px-2 py-1">
                            <option value="">-- Select Period --</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="subject">Subject: </label>
                        <select name="subject_id" id="subjectInput" class="border rounded px-2 py-1">
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="teacher">Teacher: </label>
                        <select name="teacher_id" id="teacherInput" class="border rounded px-2 py-1">
                            <option value="">-- Select Teacher --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Subject</button>
            </div>
        </form>
    </div>

    <script>
        // // Handle cell clicks
        // document.querySelectorAll('.timetable-cell').forEach(cell => {
        //     cell.addEventListener('click', function() {
        //         let day = this.getAttribute('data-day');
        //         let period = this.getAttribute('data-period');
        //         let subjectId = this.getAttribute('data-subject');
        //         let teacherId = this.getAttribute('data-teacher');

        //         // Update form inputs with selected cell's data
        //         document.getElementById('dayInput').value = day;
        //         document.getElementById('periodInput').value = period;
        //         document.getElementById('subjectInput').value = subjectId;
        //         document.getElementById('teacherInput').value = teacherId;

        //         // Set hidden form inputs
        //         document.getElementById('hiddenDay').value = day;
        //         document.getElementById('hiddenPeriod').value = period;
        //     });
        // });
    </script>
@endsection
