<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
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
                $cover = $p->cover_image;
                if ($cover && !str_starts_with($cover, 'http')) {
                    $cover = Storage::disk('public')->url($cover);
                }
                return [
                    'id' => $p->id,
                    'title' => $p->{"title_$locale"},
                    'category' => $p->category,
                    'cover_image' => $cover,
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

        $cover = $project->cover_image;
        if ($cover && !str_starts_with($cover, 'http')) {
            $cover = Storage::disk('public')->url($cover);
        }

        $gallery = collect($project->gallery)->map(function ($img) {
            if ($img && !str_starts_with($img, 'http')) {
                return Storage::disk('public')->url($img);
            }
            return $img;
        });

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
