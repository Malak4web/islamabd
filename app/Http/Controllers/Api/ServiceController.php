<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
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
        $icon = $service->icon;
        if ($icon && !str_starts_with($icon, 'http')) {
            $icon = \Illuminate\Support\Facades\Storage::disk('public')->url($icon);
        }

        $image = $service->image;
        if ($image && !str_starts_with($image, 'http')) {
            $image = \Illuminate\Support\Facades\Storage::disk('public')->url($image);
        }

        $gallery = $service->gallery ?: [];
        $gallery = array_map(function($img) {
            return (str_starts_with($img, 'http')) 
                ? $img 
                : \Illuminate\Support\Facades\Storage::disk('public')->url($img);
        }, $gallery);

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
