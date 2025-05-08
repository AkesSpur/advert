<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Profile;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
            'name' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);

        $review = Review::create($validated);

        return redirect()->back()->with('success', 'Отзыв успешно добавлен!');
    }

    public function index()
    {
        $reviews = Review::all();
        return view('admin.review.index', compact('reviews'));
    }

    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response(['status' => 'success', 'message' => 'Review deleted successfully!']);
    }
}
