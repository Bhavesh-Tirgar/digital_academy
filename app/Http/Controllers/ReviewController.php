<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    // Validate request data
    $request->validate([
        'review' => 'required|string|max:500',
        'rating' => 'required|integer|min:1|max:5',
        'course_id' => 'required|exists:courses,id',
    ]);

    // Ensure user is authenticated before saving
    if (!Auth::check()) {
        return back()->with('error', 'You must be logged in to leave a review.');
    }

    // Store the review with correct user_id
    Review::create([
        'user_id' => Auth::id(),  // Ensure this is not null
        'course_id' => $request->course_id,
        'review' => $request->review,
        'rating' => $request->rating,
    ]);

    return back()->with('success', 'Review added successfully!');
}


    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if (Auth::user()->role === 'admin') {  // Ensure role checking logic is correct
            $review->delete();
            return back()->with('success', 'Review deleted successfully!');
        }

        return back()->with('error', 'Unauthorized!');
    }
}
