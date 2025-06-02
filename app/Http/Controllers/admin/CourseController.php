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
        $request->validate(['name' => 'required|string|unique:courses,name']);
        
        Course::create(['name' => $request->name]);

        return redirect()->route('courses.index')->with('success', 'Course added successfully.');
    }

    // Show form to edit course
    public function edit(Course $course)
    {
        return view('backend.courses.edit', compact('course'));
    }

    // Update course details
    public function update(Request $request, Course $course)
    {
        $request->validate(['name' => 'required|string|unique:courses,name,'.$course->id]);

        $course->update(['name' => $request->name]);

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
    public function uploadMaterial($id, $plan)
    {
        $course = Course::findOrFail($id);
        $subjects = Subject::where('course_id', $id)->get();
        return view('backend.courses.uploadMaterial', compact('course', 'plan', 'subjects'));
    }

    // Store uploaded materials
    public function storeMaterial(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'plan_level' => 'required|string',
            'video.*' => 'mimes:mp4,mov,avi|max:50000',
            'pdf.*' => 'mimes:pdf|max:2048',
            'handwritten_notes.*' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $this->uploadFiles($request, 'video', 'materials/videos', $id);
        $this->uploadFiles($request, 'pdf', 'materials/pdfs', $id);
        $this->uploadFiles($request, 'handwritten_notes', 'materials/notes', $id);

        return redirect()->back()->with('success', 'Materials uploaded successfully.');
    }

    // Helper function to upload files
    private function uploadFiles($request, $type, $path, $courseId)
    {
        if ($request->hasFile($type)) {
            foreach ($request->file($type) as $file) {
                $filePath = $file->store($path, 'public');

                Material::create([
                    'course_id' => $courseId,
                    'subject_id' => $request->subject_id,
                    'title' => $request->input($type . '_title', 'Untitled'),
                    'file_path' => $filePath,
                    'type' => $type,
                    'plan_level' => ucfirst($request->plan_level),
                ]);
            }
        }
    }

    // Add Subject
    public function addSubject(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string|unique:subjects,name',
        ]);

        $subject = Subject::create([
            'course_id' => $request->course_id,
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'subject_id' => $subject->id
        ]);
    }

    // View materials based on selected course and plan
    public function viewMaterials($course_id, $plan)
    {
        $course = Course::find($course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $materials = Material::whereHas('subject', function ($query) use ($course) {
            $query->where('course_id', $course->id);
        })->where('plan_level', ucfirst($plan))->get();

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
        $materials = Material::whereIn('id', $request->material_ids)->get();

        foreach ($materials as $material) {
            // Delete file from storage
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            // Delete material from database
            $material->delete();
        }

        return redirect()->back()->with('success', 'Selected materials deleted successfully.');
    }

    // Delete a specific material
    public function deleteMaterial($id)
    {
        $material = Material::findOrFail($id);

        // Check if file exists in storage before deleting
        if (Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        // Delete material record from database
        $material->delete();

        return redirect()->back()->with('success', 'Material deleted successfully.');
    }
}
