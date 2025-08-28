<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class PinPostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Post $post)
    {
        $scope = $request->get('scope', 'user');

        return $scope === 'group'
            ? $this->toggleGroupPin($post)
            : $this->toggleUserPin($request->user(), $post);
    }

    private function toggleGroupPin(Post $post)
    {
        /**
         * @var Group $group
         */
        $group = $post->group;
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (! $group) {
            return response('Invalid Request', 400);
        }

        if (! $group->isAdmin($user)) {
            return response("You don't have permission to access this page.", 403);
        }

        $pinned = $group->pinned_post_id !== $post->id;
        $group->update([
            'pinned_post_id' => $pinned ? $post->id : null,
        ]);

        return back()
            ->with('success', __("Post was successfully " . ($pinned ? 'pinned' : 'unpinned')));
    }

    private function toggleUserPin(User $user, Post $post)
    {
        $pinned = $user->pinned_post_id !== $post->id;
        $user->update([
            'pinned_post_id' => $pinned ? $post->id : null,
        ]);

        return back()
            ->with('success', __("Post was successfully " . ($pinned ? 'pinned' : 'unpinned')));
    }
}
