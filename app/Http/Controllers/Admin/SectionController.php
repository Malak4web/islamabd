<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of sections for a page.
     */
    public function index(string $pageId)
    {
        $page = Page::findOrFail($pageId);
        
        return response()->json([
            'data' => $page->sections()->ordered()->get()
        ]);
    }

    /**
     * Update the specified section content.
     */
    public function update(Request $request, string $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'content' => 'required|array'
        ]);

        $section->update([
            'content' => $request->content
        ]);

        return response()->json([
            'data' => $section,
            'message' => 'Section updated successfully.'
        ]);
    }

    /**
     * Toggle section active status.
     */
    public function toggle(string $id)
    {
        $section = Section::findOrFail($id);
        $section->update(['is_active' => !$section->is_active]);

        return response()->json([
            'data' => $section,
            'message' => 'Section visibility toggled.'
        ]);
    }

    /**
     * Reorder sections.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|exists:sections,id'
        ]);

        foreach ($request->order as $index => $id) {
            Section::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json([
            'message' => 'Sections reordered successfully.'
        ]);
    }
}
