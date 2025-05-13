<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ThesisRequirement;

class ThesisRequirementController extends Controller
{
    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'thesis_title' => 'required|string|max:255',
            'forms.*' => 'file|mimes:pdf,doc,docx|max:10240',
            'hardbound' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'hardbound_url' => 'nullable|url',

        ]);
    
        $data = [
            'thesis_title' => $validated['thesis_title'],
        ];
    
        $requirements = [
            'forms' => true,  // supports multiple uploads
            'hardbound' => false,
            'final_script' => false,
            'ieee_journal' => false,
            'defense_ppt' => false,
            'user_manual' => false,
            'source_code' => false,
            'application' => false,
            'mobile_apk' => false,
            'tarpaulin_design' => false,
            'demo_video' => false,
            'promo_video' => false,
        ];
    
        foreach ($requirements as $field => $isMultiple) {
            $files = [];
    
            // Handle multiple file uploads
            if ($isMultiple && $request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $files[] = $file->store("thesis_uploads/$field");
                }
            }
    
            // Handle single file upload
            elseif (!$isMultiple && $request->hasFile($field)) {
                $files[] = $request->file($field)->store("thesis_uploads/$field");
            }
    
            // Handle optional URL
            $urlInput = $request->input($field . '_url');
            if ($urlInput) {
                $files[] = $urlInput;
            }
    
            // Save to database if anything was uploaded/provided
            if (!empty($files)) {
                $data[$field] = $isMultiple ? json_encode($files) : $files[0];
            }
        }
    
        ThesisRequirement::create($data);
    
        return redirect()->back()->with('success', 'Thesis requirements uploaded successfully!');
    }
    
}
