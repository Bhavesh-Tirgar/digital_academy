<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    // Display list of courses
    public function index()
    {
        $courses = Course::all();
        return view('backend.courses.index', compact('courses'));
    }

    // Show form to create a new course
    public function create()
    {
        return view('backend.courses.create');
    }

    // Store new course in database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:courses,name',
            'description' => 'nullable|string',
        ]);
        
        Course::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course added successfully.');
    }

    // Show form to edit course
    public function edit(Course $course)
    {
        return view('backend.courses.edit', compact('course'));
    }

    // Display single course details or redirect to edit
    public function show(Course $course)
    {
        return redirect()->route('courses.edit', $course->id);
    }

    // Update course details
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|unique:courses,name,'.$course->id,
            'description' => 'nullable|string',
        ]);

        $course->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    // Delete course and related materials
    public function destroy(Course $course)
    {
        // Delete related materials & files
        $materials = Material::whereHas('subject', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->get();

        foreach ($materials as $material) {
            Storage::disk('public')->delete($material->file_path);
            $material->delete();
        }

        // Delete the course
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    // Show upload material form
    public function uploadMaterial($id, $plan = null)
    {
        $course = Course::with('instructor')->findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && !$user->isAdmin() && $user->id !== $course->instructor_id) {
            return redirect()->route('instructor.dashboard')->with('error', 'Unauthorized access to this course syllabus.');
        }
        $subjects = Subject::where('course_id', $id)->with('materials')->get();
        $allMaterials = Material::whereIn('subject_id', $subjects->pluck('id'))->with('subject')->latest()->get();
        return view('backend.courses.uploadMaterial', compact('course', 'plan', 'subjects', 'allMaterials'));
    }

    // Store uploaded materials
    public function storeMaterial(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && !$user->isAdmin() && $user->id !== $course->instructor_id) {
            return redirect()->route('instructor.dashboard')->with('error', 'Unauthorized access to this course syllabus.');
        }

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'video_url' => 'nullable|url',
            'video_title' => 'nullable|string|max:255',
            'pdf_title' => 'nullable|string|max:255',
            'handwritten_title' => 'nullable|string|max:255',
            'video.*' => 'nullable|mimes:mp4,mov,avi,webm,mkv|max:100000',
            'pdf.*' => 'nullable|mimes:pdf|max:20000',
            'handwritten_notes.*' => 'nullable|mimes:jpg,jpeg,png,webp,pdf|max:20000',
        ]);

        $uploadedCount = 0;

        // 1. Process Video URL / Stream Embed (YouTube, Vimeo, Cloudflare, S3)
        if ($request->filled('video_url')) {
            $durationSeconds = $request->filled('duration_minutes') ? ((int) $request->input('duration_minutes') * 60) : null;
            Material::create([
                'subject_id' => $request->subject_id,
                'title' => $request->input('video_title') ?: 'Video Lesson',
                'video_url' => $request->input('video_url'),
                'file_path' => null,
                'type' => 'video',
                'duration_seconds' => $durationSeconds,
                'transcript' => $request->input('transcript'),
                'plan_level' => 'General',
            ]);
            $uploadedCount++;
        }

        // 2. Process Video Files
        if ($request->hasFile('video')) {
            $uploadedCount += $this->uploadFiles($request, 'video', 'materials/videos', $id);
        }

        // 3. Process PDF Files
        if ($request->hasFile('pdf')) {
            $uploadedCount += $this->uploadFiles($request, 'pdf', 'materials/pdfs', $id);
        }

        // 4. Process Notes / Worksheets
        if ($request->hasFile('handwritten_notes')) {
            $uploadedCount += $this->uploadFiles($request, 'handwritten_notes', 'materials/notes', $id);
        }

        if ($uploadedCount === 0) {
            return redirect()->back()->with('error', 'Please provide a video URL or select at least one file to upload.');
        }

        return redirect()->back()->with('success', "{$uploadedCount} curriculum material(s) added successfully.");
    }

    // Helper function to upload files
    private function uploadFiles($request, $type, $path, $courseId)
    {
        $count = 0;
        if ($request->hasFile($type)) {
            $dbType = ($type === 'handwritten_notes') ? 'handwritten' : $type;
            
            $titleInput = ($type === 'handwritten_notes')
                ? ($request->input('handwritten_title') ?: 'Lecture Worksheet')
                : ($request->input($type . '_title') ?: ucfirst($type) . ' Material');

            foreach ($request->file($type) as $file) {
                $filePath = $file->store($path, 'public');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $finalTitle = !empty($titleInput) && $titleInput !== 'Untitled' ? $titleInput : $originalName;

                Material::create([
                    'subject_id' => $request->subject_id,
                    'title' => $finalTitle,
                    'file_path' => $filePath,
                    'type' => $dbType,
                    'duration_seconds' => $type === 'video' && $request->filled('duration_minutes') ? ((int) $request->input('duration_minutes') * 60) : null,
                    'transcript' => $type === 'video' ? $request->input('transcript') : null,
                    'plan_level' => 'General',
                ]);
                $count++;
            }
        }
        return $count;
    }

    // Add Subject
    public function addSubject(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string',
        ]);

        $course = Course::find($request->course_id);
        $plan = $request->plan ?? ($course ? $course->name : null);

        $subject = Subject::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
            'plan' => $plan ? ucfirst($plan) : null,
        ]);

        return response()->json([
            'status' => 'success',
            'success' => true,
            'subject' => [
                'id' => $subject->id,
                'name' => $subject->name,
            ],
            'subject_id' => $subject->id
        ]);
    }

    // View materials for selected course
    public function viewMaterials($course_id, $plan = null)
    {
        $course = Course::find($course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        if (!auth()->user()->isAdmin() && $course->instructor_id !== auth()->id()) {
            return redirect()->route('instructor.dashboard')->with('error', 'Unauthorized access to course materials.');
        }

        $materialsQuery = Material::whereHas('subject', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        });
        if ($plan) {
            $materialsQuery->where('plan_level', ucfirst($plan));
        }
        $materials = $materialsQuery->with('subject')->latest()->get();

        return view('backend.courses.viewMaterials', compact('course', 'plan', 'materials'));
    }

    // Bulk delete materials
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'material_ids' => 'required|array',
            'material_ids.*' => 'exists:materials,id',
        ]);

        // Get selected materials
        $materials = Material::whereIn('id', $request->material_ids)->with('subject.course')->get();

        $deletedCount = 0;
        foreach ($materials as $material) {
            $course = $material->subject ? $material->subject->course : null;
            if (!auth()->user()->isAdmin() && (!$course || $course->instructor_id !== auth()->id())) {
                continue; // Skip if user is not admin and not the course instructor
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            // Delete material from database
            $material->delete();
            $deletedCount++;
        }

        return redirect()->back()->with('success', "{$deletedCount} material(s) deleted successfully.");
    }

    // Delete a specific material
    public function deleteMaterial($id)
    {
        $material = Material::with('subject.course')->findOrFail($id);
        $course = $material->subject ? $material->subject->course : null;

        if (!auth()->user()->isAdmin() && (!$course || $course->instructor_id !== auth()->id())) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if file exists in storage before deleting
        if (Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        // Delete material record from database
        $material->delete();

        return redirect()->back()->with('success', 'Material deleted successfully.');
    }
}
