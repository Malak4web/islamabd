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
     * Helper to format image paths to web-accessible URLs.
     */
    private function formatImageUrl(?string $path): ?string
    {
        if (!$path) return null;
        $path = preg_replace('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/#', '/storage/', $path);
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }
        return '/storage/' . ltrim($path, '/');
    }

    /**
     * Helper to transform a project model for admin API responses.
     */
    private function transformProject(Project $project): array
    {
        $data = $project->toArray();
        $data['cover_image'] = $this->formatImageUrl($project->cover_image);
        if (!empty($project->gallery) && is_array($project->gallery)) {
            $data['gallery'] = array_values(array_map(fn($img) => $this->formatImageUrl($img), $project->gallery));
        } else {
            $data['gallery'] = [];
        }
        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::orderBy('order')->get()->map(function ($project) {
            return $this->transformProject($project);
        });

        return response()->json([
            'data' => $projects
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
            'cover_image' => 'nullable|string',
            'gallery' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if (!isset($validated['order'])) {
            $validated['order'] = (int) Project::max('order') + 1;
        }

        $project = Project::create($validated);

        return response()->json([
            'data' => $this->transformProject($project),
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
            'cover_image' => 'nullable|string',
            'gallery' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $project->update($validated);

        return response()->json([
            'data' => $this->transformProject($project->fresh()),
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
            'data' => $this->transformProject($project->fresh()),
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
            'data' => $this->transformProject($project->fresh()),
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
        $request->validate(['file' => 'required|file|mimes:jpeg,png,jpg,webp,svg|max:10240']);

        $project->clearMediaCollection('cover');
        $media = $project->addMediaFromRequest('file')
            ->toMediaCollection('cover');

        $relativePath = $media->id . '/' . $media->file_name;
        $project->update(['cover_image' => $relativePath]);

        $formattedUrl = $this->formatImageUrl($relativePath);

        return response()->json([
            'data' => [
                'url' => $formattedUrl,
                'cover_image' => $formattedUrl
            ],
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
            'images' => 'required',
            'images.*' => 'file|max:25600'
        ]);

        $rawFiles = $request->file('images');
        $files = is_array($rawFiles) ? $rawFiles : [$rawFiles];

        $paths = [];
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $media = $project->addMedia($file)->toMediaCollection('gallery');
                $paths[] = $media->id . '/' . $media->file_name;
            }
        }

        $currentGallery = $project->gallery ?? [];
        $merged = array_values(array_unique(array_merge($currentGallery, $paths)));
        $project->update(['gallery' => $merged]);

        $galleryUrls = array_values(array_map(fn($img) => $this->formatImageUrl($img), $merged));

        return response()->json([
            'data' => ['gallery' => $galleryUrls],
            'message' => 'Gallery images uploaded successfully.'
        ]);
    }

    /**
     * Remove image from gallery.
     */
    public function removeGallery(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $request->validate([
            'image_id' => 'nullable|integer',
            'image' => 'nullable|string'
        ]);

        $gallery = $project->gallery ?? [];

        if ($request->filled('image_id')) {
            $media = $project->getMedia('gallery')->find($request->image_id);
            if ($media) {
                $pathToRemove = $media->id . '/' . $media->file_name;
                $media->delete();
                $gallery = array_values(array_filter($gallery, fn($path) => $path !== $pathToRemove));
            }
        } elseif ($request->filled('image')) {
            $target = $request->input('image');
            foreach ($project->getMedia('gallery') as $m) {
                $p = $m->id . '/' . $m->file_name;
                if ($p === $target || $m->getUrl() === $target || basename($p) === basename($target)) {
                    $m->delete();
                    break;
                }
            }
            $gallery = array_values(array_filter($gallery, function($path) use ($target) {
                return $path !== $target && 
                       $this->formatImageUrl($path) !== $target && 
                       basename($path) !== basename($target);
            }));
        }

        $project->update(['gallery' => $gallery]);

        $galleryUrls = array_values(array_map(fn($img) => $this->formatImageUrl($img), $gallery));

        return response()->json([
            'data' => ['gallery' => $galleryUrls],
            'message' => 'Image removed from gallery.'
        ]);
    }
}
