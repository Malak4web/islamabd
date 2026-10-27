<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        
        $services = Service::active()
            ->ordered()
            ->get()
            ->map(fn($service) => $this->localize($service, $locale));

        return response()->json(['data' => $services]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $locale = app()->getLocale();
        $service = Service::active()->findOrFail($id);
        
        return response()->json(['data' => $this->localize($service, $locale)]);
    }

    private function localize($service, $locale)
    {
        $icon = $this->formatImageUrl($service->icon);
        $image = $this->formatImageUrl($service->image);

        $gallery = $service->gallery ?: [];
        $gallery = array_values(array_filter(array_map(fn($img) => $this->formatImageUrl($img), $gallery)));

        return [
            'id' => $service->id,
            'title' => $locale === 'ar' ? $service->title_ar : $service->title_en,
            'description' => $locale === 'ar' ? $service->description_ar : $service->description_en,
            'icon' => $icon,
            'image' => $image,
            'gallery' => $gallery,
            'order' => $service->order,
            'is_active' => $service->is_active,
        ];
    }
}
