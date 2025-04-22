<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function uploadImage(array $data, ?string $oldImg = null)
    {
        $img = $data['image'];
        $img->store('images', 'public');

        if ($oldImg) {
            Storage::disk('public')->delete('images/' . $oldImg);
        }

        return $img->hashName();
    }
}
