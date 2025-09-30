<?php

namespace Support\Helpers;

use Illuminate\Support\Facades\Storage;


function UploadImage($image, $folder, $disk = 'public')
{

    if (!$image && !is_file($image)) {
        return null;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    $extension = strtolower($image->getClientOriginalExtension());

    if (!in_array($extension, $allowedExtensions)) {
        return null;
    }
    $folder = trim($folder, '/');


    $imageName = uniqid() . time() . '.' . $extension;
    $path = Storage::disk($disk)->putFileAs($folder, $image, $imageName);
    return Storage::url($path);
}
