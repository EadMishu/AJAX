<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\File;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Show all saved image records.
     */
    public function index()
    {
        $images = Image::latest()->get();

        return view('image.index', compact('images'));
    }

    /**
     * Show form to create a new image record.
     */
    public function create()
    {
        return view('image.create');
    }

    /**
     * Store new image record using image_id from File model.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'string|max:255',
            'phone'     => 'string|max:20',
            'number'    => 'string|max:20',
            'image_id'  => 'required|exists:files,id',
        ]);

        // Get file info from File model
        $file = File::find($request->image_id);

        // Create new record in images table
        Image::create([
            'name'   => $request->name,
            'phone'  => $request->phone,
            'number' => $request->number,
            'image'  => $file->name, // or $file->original_name
        ]);

        return redirect()->route('imageses.index')->with('success', 'Image record saved successfully.');
    }

    /**
     * Optional: show a single image record
     */
    public function show(Image $image)
    {
      
    }

    /**
     * Optional: edit a record
     */
    public function edit(Image $image)
    {
        return view('images.edit', compact('image'));
    }

    /**
     * Optional: update existing record
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'number'    => 'required|string|max:20',
            'image_id'  => 'required|exists:files,id',
        ]);

        $file = File::find($request->image_id);

        $image->update([
            'name'   => $request->name,
            'phone'  => $request->phone,
            'number' => $request->number,
            'image'  => $file->name,
        ]);

        return redirect()->route('images.index')->with('success', 'Image record updated.');
    }

    /**
     * Optional: delete image record
     */
    public function destroy(Image $image)
    {
        $image->delete();

        return redirect()->route('imageses.index')->with('success', 'Image record deleted.');
    }
}
