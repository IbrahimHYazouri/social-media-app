<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\Reactable;
use App\Http\Requests\StoreReactionRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ReactionController extends Controller
{
    public function __invoke(StoreReactionRequest $request, ?Post $post = null, ?Comment $comment = null): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $reactable = $this->getReactableFromRoute($post, $comment);

        $hasReaction = $reactable->toggleReactionForUser(
            $user->id,
            $request->validated('type')
        );

        return response()->json([
            'num_of_reactions' => $reactable->reaction_count,
            'user_has_reacted' => $hasReaction,
        ]);
    }

    private function getReactableFromRoute(?Post $post, ?Comment $comment): Reactable
    {
        $reactable = $post ?? $comment;

        if (! $reactable instanceof Reactable) {
            abort(404, 'Reactable resource not found');
        }

        return $reactable;
    }
}
