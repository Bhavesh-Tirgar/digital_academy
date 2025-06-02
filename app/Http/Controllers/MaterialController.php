<?php

namespace App\Http\Controllers;
use App\Models\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'video' => 'nullable|array',
            'video.*' => 'mimes:mp4,mov,avi,wmv|max:50000',
            'pdf' => 'nullable|array',
            'pdf.*' => 'mimes:pdf|max:10000',
            'handwritten_notes' => 'nullable|array',
            'handwritten_notes.*' => 'mimes:pdf,jpg,jpeg,png|max:10000',
        ]);

        $materials = [];

        if ($request->hasFile('video')) {
            foreach ($request->file('video') as $video) {
                $videoName = time() . '_' . $video->getClientOriginalName();
                $path = $video->storeAs('public/materials/videos', $videoName);
                $materials[] = [
                    'course_id' => $id,
                    'subject_id' => $request->subject_id,
                    'type' => 'video',
                    'file_path' => str_replace('public/', 'storage/', $path),
                ];
            }
        }

        if ($request->hasFile('pdf')) {
            foreach ($request->file('pdf') as $pdf) {
                $pdfName = time() . '_' . $pdf->getClientOriginalName();
                $path = $pdf->storeAs('public/materials/pdfs', $pdfName);
                $materials[] = [
                    'course_id' => $id,
                    'subject_id' => $request->subject_id,
                    'type' => 'pdf',
                    'file_path' => str_replace('public/', 'storage/', $path),
                ];
            }
        }

        if ($request->hasFile('handwritten_notes')) {
            foreach ($request->file('handwritten_notes') as $note) {
                $noteName = time() . '_' . $note->getClientOriginalName();
                $path = $note->storeAs('public/materials/notes', $noteName);
                $materials[] = [
                    'course_id' => $id,
                    'subject_id' => $request->subject_id,
                    'type' => 'handwritten',
                    'file_path' => str_replace('public/', 'storage/', $path),
                ];
            }
        }

        if (!empty($materials)) {
            Material::insert($materials);
            return back()->with('success', 'Materials uploaded successfully!');
        }

        return back()->with('error', 'No materials uploaded.');
    }// In MaterialController
    public function show($subject_id, $plan)
{
    // Fetch the course related to the subject_id
    $course = Course::whereHas('subjects', function ($query) use ($subject_id) {
        $query->where('id', $subject_id);
    })->first();

    // Fetch materials based on the plan level and subject_id
    $materials = Material::where('subject_id', $subject_id)
                         ->where('plan_level', $plan)
                         ->get();

    // Pass the course and materials to the view
    return view('frontend.materials', compact('course', 'materials', 'plan'));
}



}