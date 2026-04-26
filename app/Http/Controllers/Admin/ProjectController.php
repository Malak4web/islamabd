<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => Project::orderBy('order')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'category' => ['required', Rule::in(['residential', 'commercial', 'hospitality', 'landscape', 'retail'])],
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $project = Project::create($validated);

        return response()->json([
            'data' => $project,
            'message' => 'Project created successfully.'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $validated = $request->validate([
            'title_en' => 'string|max:255',
            'title_ar' => 'string|max:255',
            'category' => [Rule::in(['residential', 'commercial', 'hospitality', 'landscape', 'retail'])],
            'description_en' => 'string',
            'description_ar' => 'string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $project->update($validated);

        return response()->json([
            'data' => $project,
            'message' => 'Project updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(null, 204);
    }

    /**
     * Toggle active status.
     */
    public function toggle(string $id)
    {
        $project = Project::findOrFail($id);
        $project->update(['is_active' => !$project->is_active]);

        return response()->json([
            'data' => $project,
            'message' => 'Project status toggled.'
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function feature(string $id)
    {
        $project = Project::findOrFail($id);
        $project->update(['is_featured' => !$project->is_featured]);

        return response()->json([
            'data' => $project,
            'message' => 'Project featured status toggled.'
        ]);
    }

    /**
     * Reorder projects.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:projects,id'
        ]);

        foreach ($request->order as $index => $id) {
            Project::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Projects reordered successfully.']);
    }

    /**
     * Upload cover image.
     */
    public function uploadCover(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $request->validate(['file' => 'required|file|mimes:jpeg,png,jpg,webp|max:5120']);

        $project->clearMediaCollection('cover');
        $media = $project->addMediaFromRequest('file')
            ->toMediaCollection('cover');

        $project->update(['cover_image' => $media->id . '/' . $media->file_name]);

        return response()->json([
            'data' => ['url' => $media->getUrl()],
            'message' => 'Cover image uploaded successfully.'
        ]);
    }

    /**
     * Upload gallery images.
     */
    public function uploadGallery(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'file|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $paths = [];
        foreach ($request->file('images') as $file) {
            $media = $project->addMedia($file)->toMediaCollection('gallery');
            $paths[] = $media->id . '/' . $media->file_name;
        }

        $currentGallery = $project->gallery ?? [];
        $project->update(['gallery' => array_merge($currentGallery, $paths)]);

        return response()->json([
            'data' => ['gallery' => $project->getMedia('gallery')->map(fn($m) => $m->getUrl())],
            'message' => 'Gallery images uploaded successfully.'
        ]);
    }

    /**
     * Remove image from gallery.
     */
    public function removeGallery(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $request->validate(['image_id' => 'required|integer']);

        $media = $project->getMedia('gallery')->find($request->image_id);
        if ($media) {
            $pathToRemove = $media->id . '/' . $media->file_name;
            $media->delete();
            
            $gallery = $project->gallery ?? [];
            $project->update([
                'gallery' => array_values(array_filter($gallery, fn($path) => $path !== $pathToRemove))
            ]);
        }

        return response()->json([
            'data' => ['gallery' => $project->getMedia('gallery')->map(fn($m) => $m->getUrl())],
            'message' => 'Image removed from gallery.'
        ]);
    }
}
