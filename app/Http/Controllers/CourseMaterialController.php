<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Material;

class CourseMaterialController extends Controller
{
    /**
     * Show subjects based on the selected plan.
     */
    public function showSubjects($plan) {
        // Fetch subjects added by the admin for the selected plan
        $subjects = Subject::where('plan', $plan)->get();

        // Redirect to the subjects view
        return view('frontend.subjects', compact('subjects', 'plan'));
    }

    /**
     * Show course materials based on the selected subject and plan.
     */
    public function showMaterials($subject_id, $plan) {
        // Fetch materials dynamically based on plan selection
        $videos = Material::where('subject_id', $subject_id)->where('type', 'video')->get();
        $pdfs = ($plan != 'basic') ? Material::where('subject_id', $subject_id)->where('type', 'pdf')->get() : collect();
        $notes = ($plan == 'premium') ? Material::where('subject_id', $subject_id)->where('type', 'handwritten')->get() : collect();

        // Redirect to the materials view
        return view('frontend.materials', compact('videos', 'pdfs', 'notes', 'plan'));
    }
}
