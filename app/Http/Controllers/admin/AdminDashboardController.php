<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Material; // Add this
use App\Models\Review;   // Add this
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function index()
    {
        $categories = Category::all();
        $users = User::all();
        $blogs = Blog::all();
        $courses = Course::all();
        $subjects = Subject::all();
        $materials = Material::all(); // Fetch materials
        $reviews = Review::all(); // Fetch reviews

        return view('backend.dashboard', compact('users', 'categories', 'blogs', 'courses', 'subjects', 'materials', 'reviews'));
    }
}
