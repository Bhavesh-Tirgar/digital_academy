<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    /**
     * Display all materials.
     */
    public function index()
    {
        $materials = Material::all();
        return view('admin.materials.index', compact('materials'));
    }

    /**
     * Upload a material (video, PDF, or handwritten note).
     */
    public function uploadMaterial(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'file' => 'required|file|max:1048576|mimes:mp4,mov,avi,pdf,png,jpg,jpeg', // 1GB max
            'plan_level' => 'required|in:Basic,Standard,Premium',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $filename, 'public');

            // Debugging - Log file path
            Log::info('File uploaded: ' . $filePath);

            $material = Material::create([
                'subject_id' => $request->subject_id,
                'title' => $request->title,
                'type' => $file->getClientOriginalExtension(),
                'file_path' => $filePath,
                'plan_level' => $request->plan_level,
            ]);

            return redirect()->route('uploadMaterial', [
                'id' => optional($material->subject->course)->id,
                'plan' => $request->plan_level
            ])->with('success', 'Material uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Material upload failed.');
    }

    /**
     * Delete a material.
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        // Delete the file from storage
        if (Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $courseId = optional($material->subject->course)->id;
        $planLevel = $material->plan_level;
        $material->delete();

        return redirect()->route('uploadMaterial', [
            'id' => $courseId,
            'plan' => $planLevel
        ])->with('success', 'Material deleted successfully.');
    }
}
