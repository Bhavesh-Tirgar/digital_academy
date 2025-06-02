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
