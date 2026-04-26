<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index(Request $request)
    {
        $query = Setting::query();

        if ($request->has('group')) {
            $query->where('group', $request->group);
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    /**
     * Update a specific setting.
     */
    public function update(Request $request, string $key)
    {
        $request->validate([
            'value' => 'required'
        ]);

        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $request->value]
        );

        return response()->json([
            'data' => $setting,
            'message' => 'Setting updated successfully.'
        ]);
    }

    /**
     * Bulk update settings.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'present'
        ]);

        foreach ($request->settings as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value']]
            );
        }

        return response()->json([
            'message' => 'Settings saved successfully.'
        ]);
    }

    /**
     * Handle image upload for a setting (e.g., logo, favicon).
     */
    public function uploadImage(Request $request, string $key)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('settings', 'public');
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $path]
            );

            return response()->json([
                'data' => ['url' => Storage::disk('public')->url($path)],
                'message' => 'Image uploaded successfully.'
            ]);
        }

        return response()->json(['message' => 'No file uploaded.'], 400);
    }
}
