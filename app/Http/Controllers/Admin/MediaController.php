<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the media files.
     */
    public function index()
    {
        $media = MediaFile::latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'filename' => $item->filename,
                'url' => Storage::disk('public')->url($item->path),
            ];
        });

        return response()->json([
            'data' => $media
        ]);
    }

    /**
     * Store a newly created media file in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $uploadedMedia = [];

        foreach ($request->file('files') as $file) {
            $filename = $file->getClientOriginalName();
            $path = $file->store('media', 'public');

            $mediaFile = MediaFile::create([
                'filename' => $filename,
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $uploadedMedia[] = [
                'id' => $mediaFile->id,
                'filename' => $mediaFile->filename,
                'url' => Storage::disk('public')->url($mediaFile->path),
            ];
        }

        return response()->json([
            'data' => $uploadedMedia,
            'message' => 'Media uploaded successfully.'
        ], 201);
    }

    /**
     * Remove the specified media file from storage.
     */
    public function destroy(string $id)
    {
        $media = MediaFile::findOrFail($id);

        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }

        $media->delete();

        return response()->json(null, 204);
    }
}
