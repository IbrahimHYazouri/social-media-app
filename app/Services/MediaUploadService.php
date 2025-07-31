<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;

final class MediaUploadService
{
    public function upload(HasMedia $model, string $collection, UploadedFile $file): void
    {
        $existing = $model->getFirstMedia($collection);

        if ($existing && $existing->file_name === $file->getClientOriginalName()) {
            return;
        }

        $model->clearMediaCollection($collection);
        $model->addMedia($file)->toMediaCollection($collection, 'public');
    }
}
