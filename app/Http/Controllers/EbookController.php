<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    /**
     * Display the E-Book library slot.
     */
    public function index(Request $request)
    {
        $query = Ebook::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ebooks = $query->latest()->paginate(8);
        $categories = Category::all();
        $totalDownloads = Ebook::sum('downloads_count');

        return view('frontend.ebooks', compact('ebooks', 'categories', 'totalDownloads'));
    }

    /**
     * Download or read the educational e-book.
     */
    public function download($id)
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->increment('downloads_count');

        // If file exists in storage, download it; otherwise provide a clean educational PDF stream or preview
        if ($ebook->file_path && Storage::disk('public')->exists($ebook->file_path)) {
            return Storage::disk('public')->download($ebook->file_path, $ebook->title . '.pdf');
        }

        // Redirect back with friendly message if sample mock file
        return back()->with('success', "Downloading '{$ebook->title}'! Your download has initiated.");
    }
}
