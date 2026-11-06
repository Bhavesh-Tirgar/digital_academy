<?php 

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects.
     */
    public function index()
    {
        $subjects = Subject::with(['course', 'materials'])->get();
        return view('frontend.subjects', compact('subjects'));
    }
}
