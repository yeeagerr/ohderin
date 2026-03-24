<?php

namespace App\Services;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageService
{
    private $storageDir = 'products';

    /**
     * Process and store product image as WebP
     */
    public function processImage($file)
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        try {
            // Generate unique filename
            $filename = Str::uuid() . '.webp';
            $path = $this->storageDir . '/' . $filename;

            // Convert to WebP format
            $image = Image::make($file)
                ->fit(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 80); // 80% quality

            // Store the image
            \Storage::put($path, $image);

            return $path;
        } catch (\Exception $e) {
            \Log::error('Image processing error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage($imagePath)
    {
        if ($imagePath && \Storage::exists($imagePath)) {
            \Storage::delete($imagePath);
        }
    }

    /**
     * Get full URL for image
     */
    public function getImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }
        return \Storage::url($imagePath);
    }
}
