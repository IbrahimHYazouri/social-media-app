<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $posts = Post::withCount('reactions')
            ->with([
                'user',
                'reactionByCurrentUser',
                'comments' => function ($query) {
                    $query->whereNull('parent_id')
                        ->withCount('reactions')
                        ->with([
                            'user',
                            'reactionByCurrentUser',
                            'replies' => function ($q) {
                                $q->with('user', 'reactionByCurrentUser')->withCount('reactions');
                            },
                        ]);
                },
            ])->paginate(20);

        return Inertia::render('Home', [
            'feed' => PostResource::collection($posts),
            'allowed_attachment_extensions' => StorePostRequest::$extensions,
        ]);
    }
}
