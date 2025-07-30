<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $userId = Auth::id();

        $posts = Post::with([
            'user',
            'reactions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'comments' => function ($query) use ($userId) {
                $query->whereNull('parent_id')
                    ->with([
                        'user',
                        'reactions' => function ($q) {
                            $q->where('user_id', Auth::id());
                        },
                        'replies' => function ($q) {
                            $q->with([
                                'user',
                                'reactions' => function ($reactionQuery) {
                                    $reactionQuery->where('user_id', Auth::id());
                                }
                            ]);
                        },
                    ]);
            }
        ])
            ->latest()
            ->paginate(20);

        return Inertia::render('Home', [
            'feed' => PostResource::collection($posts),
            'allowed_attachment_extensions' => StorePostRequest::$extensions,
        ]);
    }
}
