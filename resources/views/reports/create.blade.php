@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-blue-800">Upload Thesis Requirements</h1>

        <form action="{{ route('reports.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Thesis Title -->
            <div class="mb-6">
                <label for="thesis_title" class="block font-medium text-gray-700 mb-2">Thesis Title *</label>
                <input type="text" id="thesis_title" name="thesis_title" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('thesis_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([
                    ['name' => 'Forms (PDF/DOC)', 'field' => 'forms', 'accept' => '.pdf,.doc,.docx', 'multiple' => true],
                    ['name' => 'Hardbound Copy', 'field' => 'hardbound', 'accept' => '.pdf,.doc,.docx'],
                    ['name' => 'Final Script', 'field' => 'final_script', 'accept' => '.pdf,.doc,.docx'],
                    ['name' => 'IEEE Journal Format', 'field' => 'ieee_journal', 'accept' => '.pdf,.doc,.docx'],
                    ['name' => 'Defense PowerPoint', 'field' => 'defense_ppt', 'accept' => '.ppt,.pptx'],
                    ['name' => 'User Manual', 'field' => 'user_manual', 'accept' => '.pdf,.doc,.docx'],
                    ['name' => 'Source Code (ZIP)', 'field' => 'source_code', 'accept' => '.zip'],
                    ['name' => 'Application (EXE)', 'field' => 'application', 'accept' => '.exe'],
                    ['name' => 'Mobile APK', 'field' => 'mobile_apk', 'accept' => '.apk'],
                    ['name' => 'Tarpaulin Design', 'field' => 'tarpaulin_design', 'accept' => '.png,.jpg,.jpeg,.pdf'],
                    ['name' => 'Demo Video (MP4)', 'field' => 'demo_video', 'accept' => '.mp4'],
                    ['name' => 'Promo Video (MP4)', 'field' => 'promo_video', 'accept' => '.mp4'],
                ] as $req)
                <div class="space-y-2">
                    <label for="{{ $req['field'] }}" class="block font-medium text-gray-700">
                        {{ $req['name'] }} <span class="text-gray-500 text-sm">(optional)</span>
                    </label>

                    <div class="flex items-center space-x-2">
                        <label class="flex-1">
                            <span class="sr-only">Choose file</span>
                            <input type="file" id="{{ $req['field'] }}" 
                                   name="{{ $req['field'] }}{{ isset($req['multiple']) ? '[]' : '' }}" 
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100"
                                   accept="{{ $req['accept'] }}"
                                   {{ isset($req['multiple']) ? 'multiple' : '' }}>
                        </label>
                    </div>

                    <p class="text-sm text-gray-500 mt-1">OR</p>

                    <input type="url" name="{{ $req['field'] }}_url" 
                           placeholder="Paste URL here" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    @error($req['field'])
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                    Submit Thesis Requirements
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
