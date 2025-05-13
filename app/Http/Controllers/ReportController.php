<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thesis;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index(Request $request)
{
    $query = Thesis::with('user')->where('status', 'approved');

    if ($request->has('author') && $request->author != '') {
        $query->where('user_id', $request->author);
    }

    if ($request->has('year') && $request->year != '') {
        $query->whereYear('created_at', $request->year);
    }

    if ($request->has('title') && $request->title != '') {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    $theses = $query->paginate(10);
    $authors = User::whereHas('theses')->get(); // or filter by role if needed
    $years = Thesis::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');

    return view('reports.index', compact('theses', 'authors', 'years'));
}

    public function create()
    {
        // Fetch approved theses of the currently authenticated user
        $theses = Thesis::where('user_id', auth()->id())
                        ->where('status', 'approved')
                        ->get();
    
        // Pass to the view as 'theses' to match the view variable name
        return view('reports.create', compact('theses'));
    }
    

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'thesis_title' => 'required|string|max:255',
            'forms.*' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'hardbound' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'final_script' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ieee_journal' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'defense_ppt' => 'nullable|file|mimes:ppt,pptx|max:2048',
            'user_manual' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'source_code' => 'nullable|file|mimes:zip,rar,7z|max:5120',
            'application' => 'nullable|file|mimes:zip,rar,7z|max:5120',
            'mobile_apk' => 'nullable|file|mimes:apk,zip|max:5120',
            'tarpaulin_design' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'demo_video' => 'nullable|file|mimes:mp4|max:10240',
            'promo_video' => 'nullable|file|mimes:mp4|max:10240',
        ]);

        $reportData = [
            'user_id' => auth()->id(),
            'thesis_id' => $request->thesis_id,
            'thesis_title' => $request->thesis_title,
        ];

        // Handle file uploads for each field
        foreach ([
            'forms', 'hardbound', 'final_script', 'ieee_journal', 
            'defense_ppt', 'user_manual', 'source_code', 'application',
            'mobile_apk', 'tarpaulin_design', 'demo_video', 'promo_video'
        ] as $field) {
            if ($request->hasFile($field)) {
                if (is_array($request->file($field))) {
                    $paths = [];
                    foreach ($request->file($field) as $file) {
                        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $file->getClientOriginalExtension());
                        $paths[] = $file->storeAs("reports/{$field}", $filename, 'public');
                    }
                    $reportData[$field] = json_encode($paths);
                } else {
                    $filename = Str::slug(pathinfo($request->file($field)->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $request->file($field)->getClientOriginalExtension());
                    $reportData[$field] = $request->file($field)->storeAs("reports/{$field}", $filename, 'public');
                }
            } elseif ($request->filled("{$field}_url")) {
                $reportData[$field] = $request->input("{$field}_url");
            }
        }

        Report::create($reportData);

        return redirect()->route('reports.index')->with('success', 'Thesis requirements submitted successfully!');
    }

    public function download($id, $field)
    {
        $report = Report::findOrFail($id);
        
        if (!isset($report->$field)) {
            abort(404);
        }

        if (filter_var($report->$field, FILTER_VALIDATE_URL)) {
            return redirect()->away($report->$field);
        }

        if (json_decode($report->$field)) {
            $files = json_decode($report->$field);
            return Storage::disk('public')->download($files[0]);
        }

        return Storage::disk('public')->download($report->$field);
    }

    public function show($id)
    {
        $report = Report::with(['user', 'thesis'])->findOrFail($id);
        return view('reports.show', compact('report'));
    }
}