<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\ThesisNotification;
use App\Models\Comment;
use App\Models\Thesis;
use App\Models\User;
use App\Models\ThesisRequirement;
use App\Notifications\ThesisCommented;
use TCPDF2DBarcode;

class ThesisController extends Controller
{
    public function index(Request $request)
    {
        $query = Thesis::query();

        if (!auth()->user()->isFaculty()) {
            $query->where('status', 'approved');
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('abstract', 'like', '%' . $search . '%')
                  ->orWhere('keywords', 'like', '%' . $search . '%')
                  ->orWhere('academic_year', 'like', '%' . $search . '%')
                  ->orWhere('month', 'like', '%' . $search . '%')
                  ->orWhere('semester', 'like', '%' . $search . '%')
                  ->orWhereRaw("LOWER(coauthors) LIKE ?", ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        $theses = $query->with('user')->paginate(10)->appends($request->only([
            'search', 'semester', 'month', 'academic_year', 'author'
        ]));

        $theses->getCollection()->transform(function ($thesis) {
            $thesis->decoded_coauthors = json_decode($thesis->coauthors ?? '[]');
            return $thesis;
        });
        
        $authors = User::whereHas('theses')
            ->with('theses')
            ->distinct()
            ->get();

        $years = Thesis::select('academic_year')->distinct()->pluck('academic_year');
        $months = Thesis::select('month')->distinct()->pluck('month');
        $semesters = Thesis::select('semester')->distinct()->pluck('semester');

        return view('theses.index', compact('theses', 'authors', 'years', 'months', 'semesters'));
    }

    public function create()
    {
        $facultyUsers = User::where('role', 'faculty')->get();
        return view('theses.create', compact('facultyUsers'));
    }

    public function store(Request $request)
    {
        // if (!auth()->check()) {
        //     return redirect()->route('login')->with('error', 'You must be logged in to upload a thesis.');
        // }

        

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'nullable|string|max:255',
            'academic_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'semester' => 'required|string|in:First Semester,Second Semester',
            'author_name' => 'required|string|max:255',

            // Co-authors are optional but should be strings
            'co_authors' => 'nullable|array',
            'co_authors.*' => 'nullable|string|max:255',

            // Adviser
            'faculty_id' => 'required|exists:users,id',

            // Main thesis file
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB

            // Thesis requirement files
            'requirement_titles' => 'nullable|array',
            'requirement_titles.*' => 'nullable|string|max:255',
            'requirement_files' => 'nullable|array',
            'requirement_files.*' => 'nullable|file|max:20240', // each 10MB
        ]);

        $customFileName = 'thesis_' . time() . '.' . $request->file('file')->getClientOriginalExtension();

        $path = $request->file('file')->storeAs('thesis/'.auth()->id(), $customFileName, 'public');
        $coAuthors = $request->input('co_authors', []);

        $thesis = Thesis::create([
            'user_id' => auth()->id(),
            'author_name' => $request->author_name, // ✅ fix
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'month' => $request->month,
            'coauthors' => json_encode($coAuthors),
            'file_path' => $path,
            'original_filename' => $request->file('file')->getClientOriginalName(),
            'status' => 'pending',
        ]);

        $this->generateQrCode($thesis);

        if ($request->hasFile('requirement_files')) {
            foreach ($request->file('requirement_files') as $index => $file) {
                if ($file) {

                    $customFileName = 'requirements_' . time() .'_'. ($request->requirement_titles[$index] ?? 'Untitled').'.' . $request->file('file')->getClientOriginalExtension();

                    $path = $file->storeAs('thesis/'.auth()->id(), $customFileName, 'public');
                    // $path = $file->store('requirements', 'public');
        
                    ThesisRequirement::create([
                        'thesis_id' => $thesis->id,
                        'title' => $request->requirement_titles[$index] ?? 'Untitled',
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        return redirect()->route('theses.index')->with('success', 'Thesis uploaded successfully.');
    }

    public function approve($id)
    {
        $thesis = Thesis::findOrFail($id);

        if (!$thesis->qr_code) {
            $qrData = route('theses.show', $thesis->id);
            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($qrData));
            $thesis->qr_code = $qrCode;
        }

        $thesis->status = 'approved';
        $thesis->save();

        return redirect()->back()->with('success', 'Thesis approved and QR code generated.');
    }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $theses = Thesis::latest()->paginate(10);
            return view('admin.dashboard', compact('theses'));
        } else {
            $theses = Thesis::where('user_id', $user->id)->latest()->paginate(10);
            return view('student.dashboard', compact('theses'));
        }
    }

    public function show($id)
    {
        $thesis = Thesis::with(['comments.user', 'user', 'requirements'])->findOrFail($id);
        return view('theses.show', compact('thesis'));
    }

    public function edit($id)
    {
        $thesis = Thesis::findOrFail($id);

        if (auth()->id() !== $thesis->user_id && !auth()->user()->isAdmin()) {
            return redirect()->route('theses.index')->with('error', 'Unauthorized access.');
        }

        return view('theses.edit', compact('thesis'));
    }

    public function update(Request $request, $id)
    {
        $thesis = Thesis::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'abstract' => 'required',
            'keywords' => 'nullable|string',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'month' => 'required|string',
            'co_authors' => 'nullable|array',
            'co_authors.*' => 'nullable|string|max:255',
            'requirement_titles' => 'array',
            'requirement_titles.*' => 'nullable|string|max:255',
            'requirement_files' => 'array',
            'requirement_files.*' => 'nullable|file|max:20240',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('theses', 'public');
            $thesis->file_path = $path;
        }

        $thesis->update([
            'author_name' => $request->author_name, // ✅ keep consistent
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'month' => $request->month,
            'coauthors' => json_encode($request->input('co_authors', [])),
        ]);

        $this->generateQrCode($thesis);

        if ($request->hasFile('requirement_files')) {
            foreach ($request->file('requirement_files') as $index => $file) {
                if ($file) {
                    $customFileName = 'requirements_' . time() .'_'. $thesis->title .'.' . $request->file('file')->getClientOriginalExtension();

                    $path = $file->storeAs('thesis/'.auth()->id(), $customFileName, 'public');
        
                    ThesisRequirement::create([
                        'thesis_id' => $thesis->id,
                        'title' => $request->requirement_titles[$index] ?? 'Untitled',
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        return redirect()->route('theses.index')->with('success', 'Thesis updated successfully.');
    }

    public function replaceThesisFile(Request $request, $id)
    {

        // if ($request->hasFile('requirement_files')) {
        //     foreach ($request->file('requirement_files') as $index => $file) {
        //         if ($file) {
        //             $path = $file->store('requirements', 'public');
        
        //             ThesisRequirement::create([
        //                 'thesis_id' => $thesis->id,
        //                 'title' => $request->requirement_titles[$index] ?? 'Untitled',
        //                 'file_path' => $path,
        //                 'original_filename' => $file->getClientOriginalName(),
        //             ]);
        //         }
        //     }
        // }
         $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:20240', 
        ]);

        $customFileName = 'thesis_' . time() . '.' . $request->file('file')->getClientOriginalExtension();

        $path = $request->file('file')->storeAs('thesis/'.auth()->id(), $customFileName, 'public');

        $thesis = Thesis::findOrFail($id);
        $thesis->file_path = $path;
        $thesis->original_filename = $request->file('file')->getClientOriginalName();
        $thesis->status = 'revised';
        $thesis->save();

        return redirect()->back()->with('success', 'Thesis marked as revised.');
    }

    public function updateStatus(Request $request,$id)
    {
        $thesis = Thesis::findOrFail($id);
        $thesis->status = $request->input('status');
        $thesis->save();

        Comment::create([
            'thesis_id' => $id,
            'user_id' => auth()->id(),
            'comment' => $request->input('comment')
        ]);

         $thesis->user->notify(new ThesisCommented($thesis, $request->input('comment')));

        return redirect()->back()->with('success', $request->input('status') == 'approved'?'Thesis has been successfully approved.':'Thesis has been marked for revision. Please wait for the student to resubmit.');
    }

    

    public function replaceRequirementFile(Request $request, $id)
    {
         $validated = $request->validate([
            'file' => 'required|file|max:20240', 
        ]);

        
        $thesis = ThesisRequirement::findOrFail($id);

        $customFileName = 'requirements_' . time() .'_'. $thesis->title .'.' . $request->file('file')->getClientOriginalExtension();

        $path = $request->file('file')->storeAs('thesis/'.auth()->id(), $customFileName, 'public');

        $thesis->file_path = $path;
        $thesis->original_filename = $request->file('file')->getClientOriginalName();
        $thesis->save();

        return redirect()->back()->with('success', 'Thesis marked as revised.');
    }


    public function destroy($id)
    {
        $thesis = Thesis::findOrFail($id);

        if (auth()->id() !== $thesis->user_id && !auth()->user()->isAdmin()) {
            return redirect()->route('theses.index')->with('error', 'Unauthorized access.');
        }

        $thesis->delete();

        return redirect()->route('theses.index')->with('success', 'Thesis deleted successfully.');
    }

    public function download($id)
    {
        $thesis = Thesis::findOrFail($id);

        if (!Storage::disk('public')->exists($thesis->file_path)) {
            return back()->with('error', 'File not found.');
        }

        if (!auth()->user()->isAdmin() && auth()->id() !== $thesis->user_id && $thesis->status !== 'approved') {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized access to this file.');
        }

        return Storage::disk('public')->download(
            $thesis->file_path,
            $thesis->original_filename ?? basename($thesis->file_path)
        );
    }

    private function generateQrCode($thesis)
    {
        $qrCode = new TCPDF2DBarcode(route('theses.show', $thesis->id), 'QRCODE,H');
        // $qrCode = QrCode::format('png')->size(200)->generate(route('theses.show', $thesis->id));
        $thesis->qr_code = base64_encode($qrCode->getBarcodePngData(6, 6, array(0,0,0)));
        $thesis->save();
    }
}
