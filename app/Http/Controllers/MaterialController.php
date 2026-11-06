<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use App\Models\Subject;
use App\Services\EdtechPerformanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /**
     * Store new learning materials (video, pdf, notes).
     */
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
                    'subject_id' => $request->subject_id,
                    'title' => pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME),
                    'type' => 'video',
                    'file_path' => str_replace('public/', '', $path),
                    'plan_level' => 'General',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($request->hasFile('pdf')) {
            foreach ($request->file('pdf') as $pdf) {
                $pdfName = time() . '_' . $pdf->getClientOriginalName();
                $path = $pdf->storeAs('public/materials/pdfs', $pdfName);
                $materials[] = [
                    'subject_id' => $request->subject_id,
                    'title' => pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME),
                    'type' => 'pdf',
                    'file_path' => str_replace('public/', '', $path),
                    'plan_level' => 'General',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($request->hasFile('handwritten_notes')) {
            foreach ($request->file('handwritten_notes') as $note) {
                $noteName = time() . '_' . $note->getClientOriginalName();
                $path = $note->storeAs('public/materials/notes', $noteName);
                $materials[] = [
                    'subject_id' => $request->subject_id,
                    'title' => pathinfo($note->getClientOriginalName(), PATHINFO_FILENAME),
                    'type' => 'handwritten',
                    'file_path' => str_replace('public/', '', $path),
                    'plan_level' => 'General',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($materials)) {
            Material::insert($materials);
            return back()->with('success', 'Materials uploaded successfully to syllabus!');
        }

        return back()->with('error', 'No materials uploaded.');
    }

    /**
     * Show learning materials workspace with lesson sidebar, progress tracking, and zero plan gates.
     */
    public function show(Request $request, $id, $plan = null)
    {
        // $id can be either a Subject ID or Course ID
        $subject = Subject::with(['course.instructor', 'course.subjects.materials', 'materials'])->find($id);

        if ($subject) {
            $course = $subject->course;
            $materials = $subject->materials;
        } else {
            $course = Course::with(['instructor', 'subjects.materials'])->findOrFail($id);
            $subject = $course->subjects->first();
            $materials = $subject ? $subject->materials : collect();
        }

        // Enforce Resource-Scoped Enrollment Gate:
        // Only the Course Author, an Administrator, or an Enrolled Student can access private learning materials.
        // Another teacher who is NOT enrolled must purchase the course just like any student.
        $user = Auth::user();
        if (!$user) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('login')
                ->with('info', "Please sign in to access '{$course->name}' learning materials.");
        }

        $isCourseAuthor = ($user->id === $course->instructor_id);
        $isAdmin = $user->isAdmin();
        $isEnrolledStudent = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$isCourseAuthor && !$isAdmin && !$isEnrolledStudent) {
            return redirect()->route('courses.checkout', $course->id)
                ->with('info', "The learning workspace and private video lessons for '{$course->name}' require active enrollment. Complete your enrollment below to get instant lifetime access!");
        }

        // Active material currently being viewed: check query param 'material_id' first
        $requestedMaterialId = $request->query('material_id');
        if ($requestedMaterialId) {
            $activeMaterial = $materials->firstWhere('id', (int) $requestedMaterialId) 
                ?? Material::find((int) $requestedMaterialId) 
                ?? $materials->first();
        } else {
            $activeMaterial = $materials->first();
        }

        // Real-time progress computation
        $userId = Auth::id() ?? 0;
        $progress = EdtechPerformanceService::getCourseProgress($userId, $course ? $course->id : 1);

        return view('frontend.materials', compact('course', 'subject', 'materials', 'activeMaterial', 'progress', 'plan'));
    }
}
