<?php

use App\Http\Controllers\ImageController;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/uploaded-files', function () {
    $files = Storage::files('public/uploads'); // বা তোমার ফাইল ফোল্ডার

    $data = array_map(function ($file) {
        return [
            'name' => basename($file),
            'url' => Storage::url($file)
        ];
    }, $files);

    return response()->json($data);
});

Route::get('/uploaded-files', [ImageController::class, 'listFiles']);
