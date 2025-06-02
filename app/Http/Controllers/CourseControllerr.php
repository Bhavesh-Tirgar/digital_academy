<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class CourseControllerr extends Controller
{
    public function selectPlan($plan)
    {
        // Fetch subjects available for the selected plan
        $subjects = Subject::where('plan', ucfirst($plan))->get();

        // Return the view with subjects based on plan
        return view('frontend.subjects', compact('subjects', 'plan'));
    }
    
}
