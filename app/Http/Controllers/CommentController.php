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

        $comment = Comment::create($validated);

        return redirect()->back()->with('success', 'Комментарий успешно добавлен!');
    }
}