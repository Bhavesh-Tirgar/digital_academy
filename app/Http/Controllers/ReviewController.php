<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'course'])->latest()->paginate(12);
        $courses = \App\Models\Course::all();
        $averageRating = round(Review::avg('rating') ?: 4.9, 1);
        $totalReviews = Review::count();
        return view('frontend.reviews', compact('reviews', 'courses', 'averageRating', 'totalReviews'));
    }

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
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Unauthorized!');
        }

        $review = Review::findOrFail($id);

        if (Auth::user()->role === 'admin' || Auth::user()->role_id == 1 || $review->user_id === Auth::id()) {
            $review->delete();
            return back()->with('success', 'Review deleted successfully!');
        }

        return back()->with('error', 'Unauthorized!');
    }
}
