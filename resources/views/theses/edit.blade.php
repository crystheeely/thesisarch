@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold">Edit Thesis</h1>

    @if($thesis->status !== 'approved')
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <strong>Note:</strong> This thesis is not approved yet. You can edit it.
    </div>
    @else
    <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
        <strong>Note:</strong> This thesis is already approved. Editing it will require re-approval.
    </div>  

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

    @endif
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
<h3 class="text-lg font-semibold mt-6 mb-2">Thesis Requirements</h3>

<div class="space-y-4">
    <div>
        <label>Forms (PDF only)</label>
        <input type="file" name="requirements[Forms]" accept=".pdf">
    </div>
    <div>
        <label>Hardbound (PDF or PNG)</label>
        <input type="file" name="requirements[Hardbound]" accept=".pdf,.png">
    </div>
    <div>
        <label>IEEE Journal (PDF)</label>
        <input type="file" name="requirements[IEEE Journal]" accept=".pdf">
    </div>
    <div>
        <label>Thesis Defense PPT (PPT or PPTX)</label>
        <input type="file" name="requirements[Thesis Defense PPT]" accept=".ppt,.pptx">
    </div>
    <div>
        <label>User Manual (PDF)</label>
        <input type="file" name="requirements[User Manual]" accept=".pdf">
    </div>
    <div>
        <label>Source Code (ZIP)</label>
        <input type="file" name="requirements[Source Code]" accept=".zip">
    </div>
    <div>
        <label>Applications (EXE, APK)</label>
        <input type="file" name="requirements[Applications]" accept=".exe,.apk">
    </div>
    <div>
        <label>Mobile Application APK (APK)</label>
        <input type="file" name="requirements[Mobile Application APK]" accept=".apk">
    </div>
    <div>
        <label>Thesis Tarpaulin Design (PNG, JPG)</label>
        <input type="file" name="requirements[Thesis Tarpaulin Design]" accept=".png,.jpg,.jpeg">
    </div>
    <div>
        <label>Demonstration Video (MP4)</label>
        <input type="file" name="requirements[Demonstration Video]" accept="video/mp4">
    </div>
    <div>
        <label>Promotional Video (MP4)</label>
        <input type="file" name="requirements[Promotional Video]" accept="video/mp4">
    </div>
</div>
<!-- Submit Button -->
<button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Upload Requirements</button>
</form>
</div>


@endsection