<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ReactionController extends Controller
{
    public function __invoke(StoreReactionRequest $request, ?Post $post = null, ?Comment $comment = null): JsonResponse
    {
        $reactable = $post ?? $comment;

        if (! $reactable) {
            abort(404);
        }

        $user = Auth::user();

        $existing = $reactable->reactions()
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $hasReaction = false;
        } else {
            $reactable->reactions()->create([
                'user_id' => $user->id,
                'type' => $request->validated('type'),
            ]);
            $hasReaction = true;
        }

        return response()->json([
            'num_of_reactions' => $reactable->reactions()->count(),
            'user_has_reacted' => $hasReaction,
        ]);
    }
}
