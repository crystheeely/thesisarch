@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('thesis.requirements.upload', $thesis->id) }}" enctype="multipart/form-data">
    @csrf
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

    <button type="submit">Upload Requirements</button>
</form>
