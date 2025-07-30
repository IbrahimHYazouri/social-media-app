<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class PostAttachmentController extends Controller
{
    public function download(Media $media): BinaryFileResponse
    {
        if ($media->model_type !== Post::class || $media->collection_name !== 'attachments') {
            abort(403, 'This file is not downloadable');
        }

        return response()->download($media->getPath());
    }
}
