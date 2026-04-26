<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CodeInjection;
use Illuminate\Http\Request;

class CodeInjectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => CodeInjection::latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string',
            'location' => 'required|in:head,body_start,body_end',
            'is_active' => 'boolean',
            'pages' => 'nullable|array'
        ]);

        $injection = CodeInjection::create($validated);

        return response()->json([
            'data' => $injection,
            'message' => 'Code injection created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'data' => CodeInjection::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $injection = CodeInjection::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string',
            'location' => 'required|in:head,body_start,body_end',
            'is_active' => 'boolean',
            'pages' => 'nullable|array'
        ]);

        $injection->update($validated);

        return response()->json([
            'data' => $injection,
            'message' => 'Code injection updated successfully.'
        ]);
    }

    /**
     * Toggle the active status of the injection.
     */
    public function toggle(string $id)
    {
        $injection = CodeInjection::findOrFail($id);
        $injection->update(['is_active' => !$injection->is_active]);

        return response()->json([
            'data' => $injection,
            'message' => 'Code injection status updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $injection = CodeInjection::findOrFail($id);
        $injection->delete();

        return response()->json(null, 204);
    }
}
