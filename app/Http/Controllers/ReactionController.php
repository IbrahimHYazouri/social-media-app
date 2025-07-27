<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ReactionController extends Controller
{
    public function __invoke(StoreReactionRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $existing = $post->reactions()
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $hasReaction = false;
        } else {
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => $data['type'],
            ]);
            $hasReaction = true;
        }

        return response()->json([
            'num_of_reactions' => $post->reactions()->count(),
            'current_user_has_reaction' => $hasReaction,
        ]);
    }
}
