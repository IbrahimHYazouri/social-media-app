<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
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
        /**
         * @var User $user
         */
        $user = Auth::user();
        $userId = Auth::id();

        $followingIds = $user->following()->select('users.id')->pluck('id');
        $groupIds = $user->groups()->pluck('groups.id');

        $posts = Post::with([
            'user',
            'reactions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
            'comments' => function ($query) {
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
                                },
                            ]);
                        },
                    ]);
            },
        ])
            ->whereIn('user_id', $followingIds)
            ->orWhereIn('group_id', $groupIds)
            ->latest()
            ->paginate(20);

        $groups = $user
            ->groups()
            ->with('authUserMembership')
            ->orderByPivot('role')
            ->orderBy('name', 'desc')
            ->get();

        return Inertia::render('Home', [
            'feed' => PostResource::collection($posts),
            'groups' => GroupResource::collection($groups),
            'allowed_attachment_extensions' => StorePostRequest::$extensions,
        ]);
    }
}
