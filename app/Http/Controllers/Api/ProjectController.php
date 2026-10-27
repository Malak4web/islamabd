<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        $query = Project::active()->ordered();

        if ($request->has('category') && $request->category !== 'all' && !empty($request->category)) {
            $query->byCategory($request->category);
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        $perPage = min((int) $request->get('per_page', 9), 24);
        $projects = $query->paginate($perPage);

        return response()->json([
            'data' => $projects->map(function($p) use ($locale) {
                return [
                    'id' => $p->id,
                    'title' => $p->{"title_$locale"},
                    'category' => $p->category,
                    'cover_image' => $this->formatImageUrl($p->cover_image),
                    'is_featured' => $p->is_featured,
                    'order' => $p->order,
                ];
            }),
            'meta' => [
                'total' => $projects->total(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'next_page_url' => $projects->nextPageUrl(),
            ]
        ]);
    }

    /**
     * Display the specified project.
     */
    public function show(Request $request, string $id)
    {
        $locale = app()->getLocale();
        $project = Project::active()->findOrFail($id);

        $cover = $this->formatImageUrl($project->cover_image);

        $gallery = collect($project->gallery)->map(function ($img) {
            return $this->formatImageUrl($img);
        })->filter()->values();

        return response()->json([
            'data' => [
                'id' => $project->id,
                'title' => $project->{"title_$locale"},
                'category' => $project->category,
                'description' => $project->{"description_$locale"},
                'cover_image' => $cover,
                'gallery' => $gallery,
                'is_featured' => $project->is_featured,
                'order' => $project->order,
            ]
        ]);
    }
}
