@extends('layouts.app')

@section('content')

    <div class="home">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Dashboard</h2>
            </div>

        </div>

        @role('Admin')
        @include('dashboard.admin')
        @endrole

        @role('Parent')
        @include('dashboard.parents')
        @endrole

        @role('Teacher')
        @include('dashboard.teacher')
        @endrole

        @role('Student')
        @include('dashboard.student')
        @endrole

    </div>

@endsection
