<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => Service::orderBy('order')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        if (!isset($validated['order'])) {
            $validated['order'] = Service::max('order') + 1;
        }

        $service = Service::create($validated);

        return response()->json([
            'data' => $service,
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
            'title_en' => 'required|string',
            'title_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $service->update($validated);

        return response()->json([
            'data' => $service,
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
            'data' => $service,
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
        $request->validate(['file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048']);

        $service->clearMediaCollection('image');
        $media = $service->addMediaFromRequest('file')->toMediaCollection('image');
        $service->update(['image' => $media->id . '/' . $media->file_name]);

        return response()->json([
            'data' => ['url' => $media->getUrl()],
            'message' => 'Image uploaded successfully.'
        ]);
    }


    /**
     * Upload service icon.
     */
    public function uploadIcon(Request $request, string $id)
    {
        $service = Service::findOrFail($id);
        $request->validate(['file' => 'required|file|max:1024']); // Icons can be SVG

        $service->clearMediaCollection('icon');
        $media = $service->addMediaFromRequest('file')->toMediaCollection('icon');
        $service->update(['icon' => $media->id . '/' . $media->file_name]);

        return response()->json([
            'data' => ['url' => $media->getUrl()],
            'message' => 'Icon uploaded successfully.'
        ]);
    }
}
