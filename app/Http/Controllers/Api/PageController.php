<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        
        $locale = app()->getLocale();
        
        $sections = $page->sections()
            ->active()
            ->ordered()
            ->get(['id', 'key', 'order', 'content', 'is_active']);

        return response()->json([
            'data' => array_merge($page->toArray(), [
                'title' => $locale === 'ar' ? $page->title_ar : $page->title_en,
                'sections' => $sections
            ])
        ]);
    }
}
