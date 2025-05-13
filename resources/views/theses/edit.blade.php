@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold">Edit Thesis</h1>

    {{-- @if($thesis->status !== 'approved')
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <strong>Note:</strong> This thesis is not approved yet. You can edit it.
    </div>
    @else
    <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
        <strong>Note:</strong> This thesis is already approved. Editing it will require re-approval.
    </div>   --}}

    <form action="{{ route('theses.update', $thesis->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label class="block font-semibold">Title:</label>
        <input type="text" name="title" value="{{ $thesis->title }}" class="w-full border p-2 rounded">

        <label class="block font-semibold mt-4">Abstract:</label>
        <textarea name="abstract" class="w-full border p-2 rounded">{{ $thesis->abstract }}</textarea>

        <label class="block font-semibold mt-4">Keywords:</label>
        <input type="text" name="keywords" value="{{ $thesis->keywords }}" class="w-full border p-2 rounded">

        <label class="block font-semibold mt-4">Upload New File (optional):</label>
        <input type="file" name="file" class="w-full border p-2 rounded">
        

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Update</button>
        <a href="{{ route('theses.show', $thesis->id) }}" class="text-gray-500 ml-4">Cancel</a>
    </form>

    {{-- @endif --}}
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Thesis Requirements</h2>

        @if($thesis->requirements)
            <ul class="list-disc pl-5">
                @foreach($thesis->requirements as $requirement)
                    <li>{{ $requirement }}</li>
                @endforeach
            </ul>
        @else
            <p>No requirements uploaded yet.</p>
        @endif

</div>
<!-- Thesis Requirements Upload -->
 <section class="bg-gray-50 border rounded p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">📄 Thesis File</h2>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            @if ($thesis->file_path)
                <div>
                    <a href="{{ asset('storage/' . $thesis->file_path) }}" download class="text-blue-600 hover:underline">
                        ⬇️ Download Thesis File
                    </a>
                </div>
            @endif

            @auth
                @if (auth()->id() === $thesis->user_id && $thesis->status !== 'approved')
                    <form action="{{ route('theses.replaceThesisFile', $thesis->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-3">
                        @csrf @method('PUT')

                        <label class="block font-medium text-sm text-gray-700 md:mr-2">Replace File:</label>
                        <input type="file" name="file" class="text-sm text-gray-700">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mt-2 md:mt-0">
                            Upload New File
                        </button>
                    </form>
                @elseif ($thesis->status === 'approved')
                    <p class="text-sm text-gray-500 italic mt-2">This thesis has been approved and can no longer be edited.</p>
                @endif
            @endauth
        </div>
    </section>

    <section class="bg-gray-50 border rounded p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-gray-800">📄 Requirements</h2>


        @foreach ($thesis->requirements as $requirement)
             <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                @if ($thesis->file_path)
                    <div>
                        <a href="{{ asset('storage/' . $requirement->file_path) }}" download class="text-blue-600 hover:underline">
                            ⬇️ Download {{$requirement->title}} File
                        </a>
                    </div>
                @endif

                @auth
                    @if (auth()->id() === $thesis->user_id && $thesis->status !== 'approved')
                        <form action="{{ route('theses.replaceRequirementFile', $requirement->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-3">
                            @csrf @method('PUT')

                            <label class="block font-medium text-sm text-gray-700 md:mr-2">Replace File:</label>
                            <input type="file" name="file" class="text-sm text-gray-700">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mt-2 md:mt-0">
                                Upload New File
                            </button>
                        </form>
                    @elseif ($thesis->status === 'approved')
                        <p class="text-sm text-gray-500 italic mt-2">This thesis has been approved and can no longer be edited.</p>
                    @endif
                @endauth
            </div>            
        @endforeach
    </section>
<!-- Submit Button -->
<button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Upload Requirements</button>
</form>
</div>


@endsection