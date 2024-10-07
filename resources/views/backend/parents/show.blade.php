@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8 bg-white rounded shadow-md p-6">
        <!-- Parent Information -->
        <h2 class="text-2xl font-bold text-gray-700 mb-6">Parent Information</h2>
        <div class="mb-6">
            <p><strong>Name:</strong> {{ $parent->user->name }}</p>
            <p><strong>Email:</strong> {{ $parent->user->email }}</p>
            <p class="capitalize"><strong>Gender:</strong> {{ $parent->user->gender }}</p>
            <p><strong>Phone:</strong> {{ $parent->user->phone }}</p>
            <p><strong>Date of Birth:</strong> {{ $parent->user->dateofbirth }}</p>
            <p><strong>Current Address:</strong> {{ $parent->user->current_address }}</p>
            <p><strong>Permanent Address:</strong> {{ $parent->user->permanent_address }}</p>
        </div>

        <!-- Children Information -->
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Children</h2>
        <table class="min-w-full bg-white rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600">ID</th>
                    <th class="px-4 py-2 text-left text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left text-gray-600">Class</th>
                    <th class="px-4 py-2 text-left text-gray-600">Date of Birth</th>
                    <th class="px-4 py-2 text-left text-gray-600">Gender</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parent->children as $child)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $child->id }}</td>
                        <td class="px-4 py-2">{{ $child->user->name }}</td>
                        <td class="px-4 py-2">{{ $child->class->class_name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $child->user->dateofbirth }}</td>
                        <td class="px-4 py-2 capitalize">{{ $child->user->gender }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
