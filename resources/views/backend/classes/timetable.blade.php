@extends('layouts.app')

@section('content')
    <div class="container mt-8">
        <h2 class="text-gray-700 uppercase font-bold">Timetable for class: {{ $class->class_name }}</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                            <td class="border px-4 py-2 text-center">
                                {{ $period }}
                                <p class="text-gray-600 italic text-xs">{{ $periodsTime[$period - 1] }}</p>
                            </td>
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

        @role('Admin')
            <!-- Adding/Updating Form -->
            <form id="timetableForm" action="{{ route('timetable.update', $class->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4">Edit Timetable Entry</h2>
                    <div class="flex justify-between">
                        <div class="mb-4">
                            <label for="day">Day: </label>
                            <select name="day" id="dayInput" class="border rounded px-2 py-1" required>
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
                            <select name="period" id="periodInput" class="border rounded px-2 py-1" required>
                                <option value="">-- Select Period --</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="subject">Subject: </label>
                            <select name="subject_id" id="subjectInput" class="border rounded px-2 py-1" required>
                                <option value="">-- Select Subject --</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="teacher">Teacher: </label>
                            <select name="teacher_id" id="teacherInput" class="border rounded px-2 py-1" required>
                                <option value="">-- Select Teacher --</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="button" id="submitBtn" class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        @endrole
    </div>

    <div id="overlay" class="fixed inset-0 bg-black opacity-50 hidden z-10"></div>

    <!-- Warning Modal -->
    <div id="warningModal" class="fixed z-20 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-6 shadow-lg max-w-lg w-full">
                <h2 class="text-xl font-bold mb-4">Warning: Conflict Detected</h2>
                <p>This day and period are already assigned to another subject in this class. Do you want to proceed?</p>
                <div class="flex justify-end mt-4">
                    <button id="cancelWarningButton" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button id="confirmWarningButton" class="bg-green-500 text-white px-4 py-2 rounded">Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed z-20 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-6 shadow-lg max-w-lg w-full">
                <h2 class="text-xl font-bold mb-4">Error: Teacher Conflict</h2>
                <p>This teacher is already assigned to another class on this day and period. You cannot proceed.</p>
                <div class="flex justify-end mt-4">
                    <button id="closeErrorButton" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#submitBtn').on('click', function() {
            const day = $('#dayInput').val();
            const period = $('#periodInput').val();
            const subjectId = $('#subjectInput').val();
            const teacherId = $('#teacherInput').val();

            // Perform AJAX request to check availability
            $.ajax({
                url: '{{ route('timetable.check', $class->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    day: day,
                    period: period,
                    subject_id: subjectId,
                    teacher_id: teacherId,
                },
                success: function(response) {
                    if (response.error) {
                        $('#overlay').removeClass('hidden');
                        $('#errorModal').removeClass('hidden');
                    } else if (response.warning) {
                        $('#overlay').removeClass('hidden');
                        $('#warningModal').removeClass('hidden');
                    } else {
                        // No conflict, submit the form directly
                        $('#timetableForm').submit();
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        });

        // Modal button handlers
        $('#cancelWarningButton').on('click', function() {
            $('#overlay').addClass('hidden');
            $('#warningModal').addClass('hidden');
        });

        $('#confirmWarningButton').on('click', function() {
            $('#overlay').addClass('hidden');
            $('#warningModal').addClass('hidden');
            $('#timetableForm').submit();
        });

        $('#closeErrorButton').on('click', function() {
            $('#overlay').addClass('hidden');
            $('#errorModal').addClass('hidden');
        });
    </script>
@endsection
