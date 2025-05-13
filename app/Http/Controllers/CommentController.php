<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Thesis;
use App\Notifications\ThesisCommented;
use Illuminate\Http\Request;
use App\Models\Notification;

class CommentController extends Controller
{

public function store(Request $request, Thesis $thesis)
{
    if (!auth()->user()->isFaculty()) {
        return redirect()->back()->with('error', 'Only admins can comment.');
    }

    $request->validate([
        'comment' => 'required|string'
    ]);

    Comment::create([
        'thesis_id' => $thesis->id,
        'user_id' => auth()->id(),
        'comment' => $request->comment
    ]);

    // Notify the student
    $thesis->user->notify(new ThesisCommented($thesis, $request->comment));

    return redirect()->route('theses.show', $thesis->id)->with('success', 'Comment added.');
}


    public function update(Request $request, Comment $comment)
    {
        // $this->authorize('update', $comment); // Optional: if using policy for access control

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'comment' => $comment->comment,
        ]);
    }


    public function edit(Comment $comment)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only admins can edit comments.');
        }
    
        return view('comments.edit', compact('comment'));
    }
    public function show(Comment $comment)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only admins can view comments.');
        }
    
        return view('comments.show', compact('comment'));
    }
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only admins can view comments.');
        }
    
        $comments = Comment::with('thesis')->paginate(10);
    
        return view('comments.index', compact('comments'));
    }
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only admins can create comments.');
        }
    
        return view('comments.create');
    }
    
    public function destroy(Comment $comment)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Only admins can delete comments.');
        }
    
        $comment->delete();
    
        return redirect()->back()->with('success', 'Comment deleted.');
    }
}