@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
    $coauthors = json_decode($thesis->coauthors, true) ?? [];
    $mainAuthor = $thesis->author_name;
    $allAuthors = $mainAuthor . (!empty($coauthors) ? ', ' . implode(', ', $coauthors) : '');
    $approvedYear = $thesis->updated_at ? $thesis->updated_at->format('Y') : 'N/A';
@endphp

<div class="container mx-auto px-4 space-y-10 mt-6">
    <!-- Title & Author -->
    <section class="border-b pb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $thesis->title }}</h1>
        <div class="text-base text-gray-700 mt-3 space-y-1">
            <p><strong>Author:</strong> {{ $mainAuthor }}</p>
            @if (!empty($coauthors))
                <p><strong>Coauthors:</strong> {{ implode(', ', $coauthors) }}</p>
            @endif
        </div>
    </section>

    <!-- Abstract & Details -->
    <section class="space-y-4 text-base leading-relaxed">
        <div>
            <strong>Abstract:</strong>
            <p class="mt-2">{{ $thesis->abstract }}</p>
        </div>
        <p><strong>Keywords:</strong> {{ $thesis->keywords ?? 'N/A' }}</p>
        <div class="text-gray-600 space-x-6 mt-2">
            <span><strong>Semester:</strong> {{ $thesis->semester }}</span>
            <span><strong>Month:</strong> {{ $thesis->month }}</span>
            <span><strong>Academic Year:</strong> {{ $thesis->academic_year }}</span>
        </div>
    </section>

    <!-- Thesis File -->
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
                        <label class="font-medium text-sm text-gray-700 md:mr-2">Replace File:</label>
                        <input type="file" name="file" class="text-sm text-gray-700" accept=".pdf,.doc,.docx">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mt-2 md:mt-0">
                            Upload New File
                        </button>
                    </form>
                @elseif ($thesis->status === 'approved')
                    <p class="text-sm text-gray-500 italic">This thesis has been approved and can no longer be edited.</p>
                @endif
            @endauth
        </div>
    </section>

    <!-- Requirements -->
    <section class="bg-gray-50 border rounded p-6 shadow-sm space-y-6">
        <h2 class="text-xl font-semibold text-gray-800">📄 Requirements</h2>

        @foreach ($thesis->requirements as $requirement)
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <a href="{{ asset('storage/' . $requirement->file_path) }}" download class="text-blue-600 hover:underline">
                        ⬇️ Download {{ $requirement->title }} File
                    </a>
                </div>

                @auth
                    @if (auth()->id() === $thesis->user_id && $thesis->status !== 'approved')
                        <form action="{{ route('theses.replaceRequirementFile', $requirement->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-3">
                            @csrf @method('PUT')
                            <label class="font-medium text-sm text-gray-700 md:mr-2">Replace File:</label>
                            <input type="file" name="file" class="text-sm text-gray-700">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 mt-2 md:mt-0">
                                Upload New File
                            </button>
                        </form>
                    @elseif ($thesis->status === 'approved')
                        <p class="text-sm text-gray-500 italic">This thesis has been approved and can no longer be edited.</p>
                    @endif
                @endauth
            </div>
        @endforeach
    </section>

    <!-- Faculty Actions -->
    @auth
        @if (auth()->user()->isFaculty() && $thesis->status !== 'approved')
            <section class="bg-gray-50 border rounded p-6 shadow-sm space-y-4">
                <div class="flex flex-wrap gap-3 pt-4">
                    @php
                        $actions = [
                            ['status' => 'approved', 'comment' => 'Approved by faculty.', 'label' => '✅ Approve', 'bg' => 'bg-green-600 hover:bg-green-700'],
                            ['status' => 'revised', 'comment' => 'Please revise and resubmit.', 'label' => '✏️ Revise', 'bg' => 'bg-yellow-500 hover:bg-yellow-600'],
                        ];
                    @endphp

                    @foreach ($actions as $action)
                        <form action="{{ route('theses.updateStatus', $thesis->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $action['status'] }}">
                            <input type="hidden" name="comment" value="{{ $action['comment'] }}">
                            <button class="{{ $action['bg'] }} text-white px-5 py-2 rounded-md text-sm shadow">
                                {{ $action['label'] }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </section>
        @endif
    @endauth

    @if (session('success'))
        <div class="p-4 bg-green-100 text-green-800 rounded mt-4">{{ session('success') }}</div>
    @endif

    <!-- Admin Comments -->
    <section class="space-y-6">
        <h2 class="text-xl font-semibold text-gray-800">💬 Admin Comments</h2>
        <div id="comments-container" class="space-y-4">
            @forelse ($thesis->comments as $comment)
                <div class="bg-white p-4 border rounded shadow-sm" id="comment-{{ $comment->id }}">
                    <div class="flex justify-between gap-4">
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-gray-600">
                                {{ $comment->user->name }}
                                <span class="text-xs text-gray-500 font-normal">
                                    • {{ $comment->created_at->format('F j, Y \a\t g:i A') }}
                                </span>
                            </p>
                            <p class="mt-1 text-gray-800" id="comment-text-{{ $comment->id }}">
                                {{ $comment->comment }}
                            </p>
                        </div>

                        @if(auth()->user()->isFaculty() && auth()->id() === $comment->user_id)
                            <div class="flex gap-2 items-start">
                                <button type="button" onclick="toggleEditForm({{ $comment->id }})" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">✏️ Edit</button>
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="delete-comment-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">🗑️ Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <form id="edit-form-{{ $comment->id }}" action="{{ route('comments.update', $comment->id) }}" method="POST" class="hidden mt-3 edit-comment-form">
                        @csrf @method('PUT')
                        <textarea name="comment" rows="3" class="w-full border p-2 rounded text-sm">{{ $comment->comment }}</textarea>
                        <div class="flex gap-2 mt-2">
                            <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Update</button>
                            <button type="button" onclick="toggleEditForm({{ $comment->id }})" class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Cancel</button>
                        </div>
                    </form>
                </div>
            @empty
                <p class="text-gray-500">No comments yet.</p>
            @endforelse
        </div>

        @if(auth()->user()->isFaculty())
            <form action="{{ route('theses.comments.store', $thesis->id) }}" method="POST" class="mt-6 space-y-3">
                @csrf
                <label for="new-comment" class="block text-sm font-medium text-gray-700">💬 Add a New Comment</label>
                <textarea name="comment" id="new-comment" rows="4" class="w-full border border-gray-300 p-3 rounded-lg shadow-sm text-sm" placeholder="Write your comment here..." required></textarea>
                <button class="bg-blue-600 text-white px-5 py-2 rounded-md text-sm hover:bg-blue-700 shadow">✍️ Submit Comment</button>
            </form>
        @endif
    </section>

    <!-- BibTeX Generator -->
    <section class="space-y-4">
        <button id="generateBibtex" class="btn-primary">📘 Generate BibTeX</button>
        <textarea id="bibtexOutput" class="w-full p-3 border rounded hidden text-sm bg-gray-50 font-mono" rows="5" readonly></textarea>
        <button id="copyBibtex" class="hidden text-sm text-blue-600 hover:underline">📋 Copy to Clipboard</button>
    </section>
</div>

@push('styles')
<style>
    .btn-primary {
        @apply bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleEditForm(id) {
        document.getElementById(`edit-form-${id}`).classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-comment-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const commentId = this.id.split('-')[2];

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-HTTP-Method-Override': 'PUT',
                        'Accept': 'application/json'
                    },
                    body: formData
                }).then(res => res.json())
                  .then(data => {
                      if (data.success) {
                          document.getElementById(`comment-text-${commentId}`).textContent = data.comment;
                          toggleEditForm(commentId);
                          alert('Comment updated successfully');
                      }
                }).catch(() => alert('Error updating comment.'));
            });
        });

        document.querySelectorAll('.delete-comment-form').forEach(form => {
            form.addEventListener('submit', e => {
                if (!confirm('Are you sure you want to delete this comment?')) {
                    e.preventDefault();
                }
            });
        });

        document.getElementById('generateBibtex').addEventListener('click', function () {
            const bibtex = `@article{thesis{{ $thesis->id }},
                author = { {{ $allAuthors }} },
                title = { {{ $thesis->title }} },
                year = { {{ $approvedYear }} },
                journal = {Thesis Repository},
                keywords = { {{ $thesis->keywords ?? '' }} }
            }`;
            const output = document.getElementById('bibtexOutput');
            output.value = bibtex;
            output.classList.remove('hidden');
            document.getElementById('copyBibtex').classList.remove('hidden');
        });

        document.getElementById('copyBibtex').addEventListener('click', function () {
            const output = document.getElementById('bibtexOutput');
            output.select();
            document.execCommand('copy');
            const original = this.innerText;
            this.innerText = '✅ Copied!';
            setTimeout(() => { this.innerText = original; }, 2000);
        });
    });
</script>
@endpush
@endsection
