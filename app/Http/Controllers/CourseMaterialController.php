<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Material;

class CourseMaterialController extends Controller
{
    /**
     * Show subjects for courses (redirects to catalog or displays subject modules).
     */
    public function showSubjects($plan = null) {
        $subjects = Subject::with(['course', 'materials'])->get();
        return view('frontend.subjects', compact('subjects'));
    }

    /**
     * Show course materials without arbitrary plan restrictions (all videos, pdfs, notes unlocked).
     */
    public function showMaterials($subject_id, $plan = null) {
        $subject = Subject::with(['course', 'materials'])->findOrFail($subject_id);
        $course = $subject->course;
        $materials = $subject->materials;

        return view('frontend.materials', compact('subject', 'course', 'materials'));
    }
}
