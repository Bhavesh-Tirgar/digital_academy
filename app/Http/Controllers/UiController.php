<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Review;
use App\Models\Course; // Import Course model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UiController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $blogs = Blog::all();
        $reviews = Review::all();
        $courses = Course::all(); // Fetch courses from database

        return view('frontend.index', compact('categories', 'blogs', 'reviews', 'courses'));
    }
}
