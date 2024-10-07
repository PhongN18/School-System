{{-- @dd($childrenInfo->all()) --}}
<style>
    .child-block {
        transition: 0.2s ease;
    }

    .child-block:hover {
        transform: scale(1.02);
        background-color: #ccc;
    }

    .child-header {
        position: relative;
    }

    .child-id {
        position: absolute;
        top: 0;
        right: 0;
    }
</style>
<h1 class="text-2xl font-bold text-center text-gray-700">Your Children</h1>
<div class="flex">
    @foreach ($childrenInfo as $child)
        <div class="w-1/2 block mt-8">
            <div class="w-full flex justify-center">
                <a href="{{ route('student.show', $child->student_id) }}"
                    class="child-block block w-4/5 bg-gray-200 border border-gray-300 rounded px-8 py-6 my-4 sm:my-0">
                    <h3 class="text-gray-700 uppercase font-bold">
                        <div class="child-header mb-3">
                            <p class="child-id text-sm italic">SID: {{ $child->student_id }}</p>
                            <p class="leading-tight text-xl">{{ $child->name }}</p>
                        </div>
                        <div class="child-info text-sm flex justify-between">
                            <span>Class: {{ $child->classname }}</span>
                            <span>Teacher: {{ $child->teacher_name }}</span>
                        </div>
                    </h3>
                </a>
            </div>
        </div>
    @endforeach
</div>
<div class="w-full block mt-4 sm:mt-8">
    <div class="flex flex-wrap sm:flex-no-wrap justify-between">

    </div>
</div> <!-- ./END PARENT -->
