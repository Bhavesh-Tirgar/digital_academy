<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('backend.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'title' => 'required|string|max:255|unique:blogs,title',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Ensure category exists
            $category = Category::findOrFail($request->category_id);

            // Upload Image
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/blog-images', $imageName);

            // Save Blog
            Blog::create([
                'image' => $imageName,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'category_id' => $category->id,
                'user_id' => Auth::id() ?? 1, // ✅ Ensure user_id is set
                'status' => 'published',
            ]);

            DB::commit();
            return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create blog: ' . $e->getMessage());
        }
    }

    /**
     * Display a single blog.
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('backend.blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = Category::all();
        return view('backend.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $data = $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'title' => 'required|string|max:255|unique:blogs,title,' . $id,
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Ensure category exists
            $category = Category::findOrFail($request->category_id);

            // Check if a new image is uploaded
            if ($request->hasFile('image')) {
                if ($blog->image && File::exists(storage_path("app/public/blog-images/{$blog->image}"))) {
                    File::delete(storage_path("app/public/blog-images/{$blog->image}"));
                }

                $image = $request->file('image');
                $imageName = uniqid() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/blog-images', $imageName);
                $data['image'] = $imageName;
            }

            // Update slug dynamically
            $data['slug'] = Str::slug($request->title);
            $data['category_id'] = $category->id;

            $blog->update($data);

            DB::commit();
            return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update blog: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($blog->image && File::exists(storage_path("app/public/blog-images/{$blog->image}"))) {
                File::delete(storage_path("app/public/blog-images/{$blog->image}"));
            }

            $blog->delete();
            DB::commit();
            return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete blog: ' . $e->getMessage());
        }
    }
}
