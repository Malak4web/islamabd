<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
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
     * Transform a service model for admin API responses.
     */
    private function transformService(Service $service): array
    {
        $data = $service->toArray();
        $data['image'] = $this->formatImageUrl($service->image);
        $data['icon'] = $this->formatImageUrl($service->icon);
        if (!empty($service->gallery) && is_array($service->gallery)) {
            $data['gallery'] = array_values(array_map(fn($img) => $this->formatImageUrl($img), $service->gallery));
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
        $services = Service::orderBy('order')->get()->map(function ($service) {
            return $this->transformService($service);
        });

        return response()->json([
            'data' => $services
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
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'gallery' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if (!isset($validated['order'])) {
            $validated['order'] = (int) Service::max('order') + 1;
        }

        $service = Service::create($validated);

        return response()->json([
            'data' => $this->transformService($service),
            'message' => 'Service created successfully.'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'gallery' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $service->update($validated);

        return response()->json([
            'data' => $this->transformService($service->fresh()),
            'message' => 'Service updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(null, 204);
    }

    /**
     * Toggle active status.
     */
    public function toggle(string $id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);

        return response()->json([
            'data' => $this->transformService($service->fresh()),
            'message' => 'Service visibility toggled.'
        ]);
    }

    /**
     * Reorder services.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|exists:services,id'
        ]);

        foreach ($request->order as $index => $id) {
            Service::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json([
            'message' => 'Services reordered successfully.'
        ]);
    }

    /**
     * Upload service image.
     */
    public function uploadImage(Request $request, string $id)
    {
        $service = Service::findOrFail($id);
        $request->validate(['file' => 'required|file|max:25600']);

        $service->clearMediaCollection('image');
        $media = $service->addMediaFromRequest('file')->toMediaCollection('image');
        $relativePath = $media->id . '/' . $media->file_name;
        $service->update(['image' => $relativePath]);

        $formattedUrl = $this->formatImageUrl($relativePath);

        return response()->json([
            'data' => [
                'url' => $formattedUrl,
                'image' => $formattedUrl
            ],
            'message' => 'Image uploaded successfully.'
        ]);
    }

    /**
     * Upload service icon.
     */
    public function uploadIcon(Request $request, string $id)
    {
        $service = Service::findOrFail($id);
        $request->validate(['file' => 'required|file|max:10240']);

        $service->clearMediaCollection('icon');
        $media = $service->addMediaFromRequest('file')->toMediaCollection('icon');
        $relativePath = $media->id . '/' . $media->file_name;
        $service->update(['icon' => $relativePath]);

        $formattedUrl = $this->formatImageUrl($relativePath);

        return response()->json([
            'data' => [
                'url' => $formattedUrl,
                'icon' => $formattedUrl
            ],
            'message' => 'Icon uploaded successfully.'
        ]);
    }
}
