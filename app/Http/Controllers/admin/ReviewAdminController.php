<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewAdminController extends Controller
{
    // Show all reviews with related user and course
    public function index()
    {
        $reviews = Review::with(['user', 'course'])->latest()->paginate(10);
        return view('backend.reviews.index', compact('reviews'));
    }

    // Show edit review form
    public function edit($id)
    {
        $review = Review::with(['user', 'course'])->findOrFail($id);
        return view('backend.reviews.edit', compact('review'));
    }

    // Update review content and rating
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $request->validate([
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update([
            'review' => $request->review,
            'rating' => $request->rating,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully!');
    }

    // Delete a review
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return redirect()->route('admin.reviews.index')->with('error', 'Review not found!');
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully!');
    }
}
