<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['approved'] = false;
        $comment = Comment::create($validated);

        return redirect()->back()->with('success', 'Комментарий успешно добавлен!');
    }

    public function index()
    {
        $comments = Comment::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.comment.index', compact('comments'));
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response(['status' => 'success', 'message' => 'Comment deleted successfully!']);
    }

    /**
     * Approve a comment.
     */
    public function approve(string $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approved = true;
        $comment->save();

        return redirect()->route('admin.comments.index')->with('success', 'Комментарий успешно одобрен!');
    }
}