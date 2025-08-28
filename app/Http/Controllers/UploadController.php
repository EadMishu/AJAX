<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class UploadController extends Controller
{
    // Return list of uploaded files, latest first
    public function index()
    {
        // Only select needed fields for efficiency
        $files = File::latest()->get(['id', 'name', 'original_name']);
        return response()->json($files);
    }

    // Handle image upload
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048', // max 2MB
        ]);

        // Store file in storage/app/public/uploads
        $path = $request->file('file')->store('uploads', 'public');

        // Save file info to DB
        $file = File::create([
            'original_name' => $request->file('file')->getClientOriginalName(),
            'name' => basename($path),    // only filename, no path
            'user_id' => auth()->id(),    // make sure user is logged in or handle nullable
            'size' => $request->file('file')->getSize(),
            'extension' => $request->file('file')->getClientOriginalExtension(),
            'type' => $request->file('file')->getMimeType(),
            'external_link' => null,
        ]);

        return response()->json($file);
    }

    public function view(Request $request)
{
    $sort_by = $request->sort ?? 'newest';

    $all_uploads = File::query(); // or your appropriate model

    switch ($sort_by) {
        case 'oldest':
            $all_uploads->orderBy('created_at', 'asc');
            break;
        case 'smallest':
            $all_uploads->orderBy('size', 'asc');
            break;
        case 'largest':
            $all_uploads->orderBy('size', 'desc');
            break;
        default:
            $all_uploads->orderBy('created_at', 'desc');
            break;
    }

    return response()->json($all_uploads->get());
}
}